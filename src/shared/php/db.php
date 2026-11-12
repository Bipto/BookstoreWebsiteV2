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

class PostgresCompiler extends QueryCompiler
{
    protected function buildOperationString(QueryBuilder $queryBuilder, array &$params, int &$parameterCounter): string
    {
        $sqlOutput = '';
        [$table, $alias] = $queryBuilder->getTable();

        switch ($queryBuilder->getQueryType()) {
            case QueryType::SELECT: {
                    $sqlOutput = 'SELECT ';

                    $selects = $queryBuilder->getSelects();
                    $selectCount = count($selects);

                    if ($selectCount === 0) {
                        $sqlOutput .= " *";
                    } else {
                        for ($index = 0; $index < $selectCount; $index++) {
                            $select = $selects[$index];
                            if ($select instanceof ColumnName) {
                                $sqlOutput .= $select->columnName;

                                if ($select->alias) {
                                    $sqlOutput .= " AS {$select->alias}";
                                }

                                if ($selectCount > 1 && $index < $selectCount - 1) {
                                    $sqlOutput .= ', ';
                                }
                            } else if ($select instanceof ColumnCount) {
                                $sqlOutput .= "COUNT({$select->columnName})";

                                if ($select->alias) {
                                    $sqlOutput .= " AS {$select->alias}";
                                }

                                if ($selectCount > 1 && $index < $selectCount - 1) {
                                    $sqlOutput .= ', ';
                                }
                            } else if ($select instanceof Constant) {
                                $sqlOutput .= " {$select->value}";
                            } else {
                                throw new Exception("Failed to find a valid select type");
                            }
                        }
                    }

                    $sqlOutput .= " FROM {$table}";

                    if ($alias !== null) {
                        $sqlOutput .= " AS {$alias}";
                    }

                    break;
                }
            case QueryType::INSERT: {
                    $keysSQL = '';
                    $valuesSQL = '';

                    $inserts = $queryBuilder->getInserts();
                    $valueCount = count($inserts);

                    for ($index = 0; $index < $valueCount; $index++) {
                        $insert = $inserts[$index];
                        $bindingName = $this->createBindingParameterName($parameterCounter);

                        $keysSQL .= $insert['key'];
                        $valuesSQL .= $bindingName;
                        $params[$bindingName] = $insert['value'];

                        if ($valueCount > 1 && $index < $valueCount - 1) {
                            $keysSQL .= ", ";
                            $valuesSQL .= ", ";
                        }
                    }

                    $sqlOutput = "INSERT INTO {$table} ({$keysSQL}) VALUES ({$valuesSQL})";
                    break;
                }
            case QueryType::DELETE: {
                    $sqlOutput = "DELETE FROM {$table}";
                    break;
                }
            default:
                throw new Exception("Invalid query type");
        }

        return $sqlOutput;
    }
    protected function buildWhereString(QueryBuilder $queryBuilder, array &$params, int &$parameterCounter): string
    {
        $sqlOutput = '';

        $wheres = $queryBuilder->getWheres();
        $whereCount = count($wheres);

        if ($whereCount > 0) {
            $sqlOutput .= ' WHERE ';
            $whereIndex = 0;

            foreach ($wheres as $where) {
                switch ($where['type']) {
                    case 'column': {
                            $value = $where['value'];
                            if ($value instanceof Constant) {
                                $generatedParamName = $this->createBindingParameterName($parameterCounter);
                                $sqlOutput .= "{$where['key']} {$where['operator']} {$generatedParamName}";
                                $params[$generatedParamName] = $value->value;
                            } else if ($value instanceof ColumnName) {
                                $sqlOutput .= "{$where['key']} {$where['operator']} {$value->columnName}";
                            } else {
                                throw new Exception("Invalid where clause");
                            }

                            if ($whereIndex++ !== $whereCount - 1) {
                                $sqlOutput .= " {$where['join']} ";
                            }

                            break;
                        }
                    case 'in': {
                            $data = $where['values'];
                            if (is_array($data)) {
                                $placeholders = [];
                                foreach ($data as $value) {
                                    $generatedParamName = $this->createBindingParameterName($parameterCounter);
                                    $params[$generatedParamName] = $value;
                                    $placeholders[] = $generatedParamName;
                                }
                                $paramsArray = implode(', ', $placeholders);
                                $sqlOutput .= "{$where['key']} IN ({$paramsArray})";
                            } else if ($data instanceof ColumnName) {
                                $sqlOutput .= "{$where['key']} IN {$value->columnName}";
                            } else {
                                throw new Exception("Invalid IN clause");
                            }

                            break;
                        }
                    case 'exists': {
                            $query = $where['query'];
                            $compiledQuery = $this->compileImpl($query, $parameterCounter);

                            $sqlOutput .= "EXISTS ({$compiledQuery->getSQL()})";

                            foreach ($compiledQuery->getBindings() as $key => $value) {
                                $params[$key] = $value;
                            }

                            if ($whereIndex++ !== $whereCount - 1) {
                                $sqlOutput .= " {$where['join']} ";
                            }
                            break;
                        }
                    default:
                        throw new Exception('Invalid where clause');
                }
            }
        }

        return $sqlOutput;
    }
    protected  function buildJoinString(QueryBuilder $queryBuilder): string
    {
        $joins = $queryBuilder->getJoins();

        if (count($joins) === 0)
            return '';

        $sql = '';
        foreach ($joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']}";

            if ($join['alias'] !== null) {
                $sql .= " AS {$join['alias']}";
            }

            $sql .= " ON {$join['column1']} = {$join['column2']}";
        }

        return $sql;
    }
    protected function buildOffsetLimitString(QueryBuilder $queryBuilder): string
    {
        $limit = $queryBuilder->getLimit();
        $offset = $queryBuilder->getOffset();

        $sql = '';

        if ($limit != null) {
            $sql .= " LIMIT {$limit}";
        }

        if ($offset != null) {
            $sql .= " OFFSET {$offset}";
        }

        return $sql;
    }
    protected function buildGroupByString(QueryBuilder $queryBuilder): string
    {
        $groupBys = $queryBuilder->getGroupBys();

        if (count($groupBys) === 0) {
            return '';
        }

        return ' GROUP BY '
            . implode(', ', $groupBys);
    }
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

    private function createBindingParameterName(int &$parameterCounter): string
    {
        return ':p' . $parameterCounter++;
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

    private function executeImpl(QueryBuilder $query): PDOStatement
    {
        $sqlCompiler = new PostgresCompiler();
        $compiledQuery = $sqlCompiler->compile($query);

        $stmt = $this->pdo->prepare($compiledQuery->getSQL());
        $stmt->execute($compiledQuery->getBindings());
        return $stmt;
    }

    public function execute(QueryBuilder $query): void
    {
        $this->executeImpl($query);
    }

    public function executeAndReturnAll(QueryBuilder $query)
    {
        $stmt = $this->executeImpl($query);
        return $stmt->fetchAll();
    }

    public function executeAndReturnOne(QueryBuilder $query)
    {
        $stmt = $this->executeImpl($query);
        return $stmt->fetch();
    }
}
