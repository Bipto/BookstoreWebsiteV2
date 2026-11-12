<?php

enum QueryType
{
    case SELECT;
    case INSERT;
    case DELETE;
}

class CompiledQuery
{
    public function __construct(private string $sql, private array $bindings) {}

    public function getSQL(): string
    {
        return $this->sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }
}

abstract class QueryCompiler
{
    public function compile(QueryBuilder $queryBuilder): CompiledQuery
    {
        $parameterCount = 0;
        return $this->compileImpl($queryBuilder, $parameterCount);
    }

    protected function createBindingParameterName(int &$parameterCounter): string
    {
        return ':p' . $parameterCounter++;
    }

    protected function compileImpl(QueryBuilder $queryBuilder, int &$parameterCounter): CompiledQuery
    {
        $sqlOutput = '';
        $paramBindings = [];

        $sqlOutput .= $this->buildOperationString($queryBuilder, $paramBindings, $parameterCounter);
        $sqlOutput .= $this->buildJoinString($queryBuilder);
        $sqlOutput .= $this->buildWhereString($queryBuilder, $paramBindings, $parameterCounter);
        $sqlOutput .= $this->buildGroupByString($queryBuilder);
        $sqlOutput .= $this->buildOffsetLimitString($queryBuilder);

        return new CompiledQuery($sqlOutput, $paramBindings);
    }

    abstract protected function buildOperationString(QueryBuilder $queryBuilder, array &$params, int &$parameterCounter): string;
    abstract protected function buildWhereString(QueryBuilder $queryBuilder, array &$params, int &$parameterCounter): string;
    abstract protected  function buildJoinString(QueryBuilder $queryBuilder): string;
    abstract protected function buildOffsetLimitString(QueryBuilder $queryBuilder): string;
    abstract protected function buildGroupByString(QueryBuilder $queryBuilder): string;
}

class ColumnName
{
    public function __construct(public readonly string $columnName, public readonly ?string $alias = null) {}
}

class ColumnCount
{
    public function __construct(public readonly string $columnName, public readonly ?string $alias = null) {}
}

class Constant
{
    public function __construct(public readonly mixed $value) {}
}

class QueryBuilder
{
    private ?QueryType $queryType = null;

    private array $selects = [];
    private array $inserts = [];
    private array $wheres = [];
    private array $groupBys = [];
    private array $joins = [];
    private ?int $offset = null;
    private ?int $limit = null;

    private function __construct(private string $table, private ?string $alias) {}

    public static function table(string $table, ?string $alias = null): QueryBuilder
    {
        return new QueryBuilder($table, $alias);
    }

    public function select(ColumnName|ColumnCount|Constant ...$columns): self
    {
        $this->queryType = QueryType::SELECT;

        foreach ($columns as $column) {
            $this->selects[] = $column;
        }

        return $this;
    }

    public function insert(array $values): self
    {
        $this->queryType = QueryType::INSERT;

        $count = count($values);
        $keys = array_keys($values);
        $values = array_values($values);

        for ($i = 0; $i < $count; $i++) {
            $this->inserts[] = [
                'key' => $keys[$i],
                'value' => $values[$i]
            ];
        }

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function delete(): self
    {
        $this->queryType = QueryType::DELETE;
        return $this;
    }

    public function whereEqual(string $column, ColumnName|Constant $value): self
    {
        $this->wheres[] = [
            'type' => 'column',
            'key' => $column,
            'value' => $value,
            'join' => 'AND',
            'operator' => '='
        ];

        return $this;
    }

    public function whereLike(string $column, ColumnName|Constant $value): self
    {
        $this->wheres[] = [
            'type' => 'column',
            'key' => $column,
            'value' => $value,
            'join' => 'AND',
            'operator' => 'LIKE'
        ];

        return $this;
    }

    public function whereILike(string $column, ColumnName|Constant $value): self
    {
        $this->wheres[] = [
            'type' => 'column',
            'key' => $column,
            'value' => $value,
            'join' => 'AND',
            'operator' => 'ILIKE'
        ];

        return $this;
    }

    public function whereIn(string $column, ColumnName|array $values): self
    {
        $this->wheres[] = [
            'type' => 'in',
            'key' => $column,
            'values' => $values,
            'join' => 'AND',
        ];

        return $this;
    }

    public function whereExists(QueryBuilder $query): self
    {
        $this->wheres[] = [
            'type' => 'exists',
            'query' => $query,
            'join' => 'AND'
        ];

        return $this;
    }

    public function groupBy(array $columns): self
    {
        if ($columns === []) {
            throw new InvalidArgumentException(
                'At least one GROUP BY column is required.'
            );
        }

        foreach ($columns as $column) {
            $this->groupBys[] = $column;
        }

        return $this;
    }

    private function recordJoin(string $joinType, string $table, string $column1, string $column2, ?string $alias): self
    {
        $join = [
            'type' => $joinType,
            'table' => $table,
            'column1' => $column1,
            'column2' => $column2,
        ];

        if (isset($alias)) {
            $join['alias'] = $alias;
        }

        $this->joins[] = $join;

        return $this;
    }

    public function leftJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        return $this->recordJoin('LEFT', $table, $column1, $column2, $alias);
    }

    public function rightJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        return $this->recordJoin('RIGHT', $table, $column1, $column2, $alias);
    }

    public function innerJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        return $this->recordJoin('INNER', $table, $column1, $column2, $alias);
    }

    public function getTable(): array
    {
        if (!$this->table)
            throw new Exception("No table has been selected");

        return [$this->table, $this->alias];
    }

    public function getQueryType(): QueryType
    {
        if (!$this->queryType)
            throw new Exception("No query type has been selected");

        return $this->queryType;
    }

    public function getSelects(): array
    {
        return $this->selects;
    }

    public function getInserts(): array
    {
        return $this->inserts;
    }

    public function getWheres(): array
    {
        return $this->wheres;
    }

    public function getGroupBys(): array
    {
        return $this->groupBys;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}

class RelationalDatabase
{
    private PDO $pdo;

    public function __construct(
        string $driver,
        string $host,
        string $port,
        string $db,
        string $user,
        string  $password,
        string $charset
    ) {

        $dsn = "$driver:host=$host;port=$port;dbname=$db";

        if ($driver === 'mysql') {
            $dsn .= ";charset=$charset";
        }

        $this->pdo = new PDO(
            $dsn,
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    private function executeImpl(QueryCompiler $compiler, QueryBuilder $query): PDOStatement
    {
        $compiledQuery = $compiler->compile($query);

        $stmt = $this->pdo->prepare($compiledQuery->getSQL());
        $stmt->execute($compiledQuery->getBindings());
        return $stmt;
    }

    public function execute(QueryCompiler $compiler, QueryBuilder $query): void
    {
        $this->executeImpl($compiler, $query);
    }

    public function executeAndReturnAll(QueryCompiler $compiler, QueryBuilder $query)
    {
        $stmt = $this->executeImpl($compiler, $query);
        return $stmt->fetchAll();
    }

    public function executeAndReturnOne(QueryCompiler $compiler, QueryBuilder $query)
    {
        $stmt = $this->executeImpl($compiler, $query);
        return $stmt->fetch();
    }
}
