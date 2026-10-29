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

class QueryBuilder
{
    private ?QueryType $queryType = null;
    private string $operationString = '';

    private $bindingValues = [];
    private string $whereString = '';

    private string $joinString = '';
    private string $groupByString = '';

    private int $parameterCounter = 0;

    private array $selects = [];
    private array $inserts = [];
    private array $wheres = [];
    private array $groupBys = [];
    private array $joins = [];

    private function __construct(private string $table, private ?string $alias) {}

    public static function table(string $table, ?string $alias = null): QueryBuilder
    {
        return new QueryBuilder($table, $alias);
    }

    private function createBindingParameterName(): string
    {
        return ':p' . $this->parameterCounter++;
    }

    public function select(array $columns = ['*']): self
    {
        if ($columns === []) {
            throw new InvalidArgumentException(
                'At least one column must be selected.'
            );
        }

        $this->queryType = QueryType::SELECT;

        $this->operationString = 'SELECT '
            . implode(', ', $columns)
            . " FROM {$this->table}";

        if ($this->alias !== null) {
            $this->operationString .= " AS {$this->alias}";
        }

        foreach ($columns as $column) {
            $this->selects[] = [
                'type' => 'column',
                'columnName' => $column
            ];
        }

        return $this;
    }

    public function insert(array $values): self
    {
        $this->queryType = QueryType::INSERT;

        $keysSQL = '(';
        $valuesSQL = '(';

        $count = count($values);
        $keys = array_keys($values);
        $values = array_values($values);

        for ($i = 0; $i < $count; $i++) {
            $paramName = $this->createBindingParameterName();

            $keysSQL .= $keys[$i];
            $valuesSQL .= $paramName;

            $this->bindingValues[$paramName] = $values[$i];

            if ($i !== $count - 1) {
                $keysSQL .= ', ';
                $valuesSQL .= ', ';
            }
        }

        $keysSQL .= ')';
        $valuesSQL .= ')';

        $this->operationString =
            "INSERT INTO {$this->table} {$keysSQL} VALUES {$valuesSQL}";

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

    public function delete(): self
    {
        $this->queryType = QueryType::DELETE;
        $this->operationString = "DELETE FROM {$this->table}";
        return $this;
    }

    public function where(array $where): self
    {
        $this->whereString = ' WHERE ';

        $count = count($where);
        $keys = array_keys($where);
        $values = array_values($where);

        for ($i = 0; $i < $count; $i++) {
            $paramName = $this->createBindingParameterName();
            $this->whereString .= "{$keys[$i]} = {$paramName}";

            $this->bindingValues[$paramName] = $values[$i];

            if ($i !== $count - 1) {
                $this->whereString .= ' AND ';
            }

            $this->wheres[] = [
                'type' => 'column',
                'key' => $keys[$i],
                'value' => $values[$i],
                'join' => 'AND',
                'operator' => '='
            ];
        }

        return $this;
    }

    public function groupBy(array $columns): self
    {
        if ($columns === []) {
            throw new InvalidArgumentException(
                'At least one GROUP BY column is required.'
            );
        }

        $this->groupByString = ' GROUP BY '
            . implode(', ', $columns);

        foreach ($columns as $column) {
            $this->groupBys[] = $column;
        }

        return $this;
    }

    private function join(string $joinType, string $table, string $column1, string $column2, ?string $alias): self
    {
        $this->joinString .= " {$joinType} JOIN {$table}";

        if ($alias !== null) {
            $this->joinString .= " AS {$alias}";
        }

        $this->joinString .= " ON {$column1} = {$column2}";

        return $this;
    }

    private function recordJoin(string $joinType, string $table, string $column1, string $column2, ?string $alias)
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
    }

    public function leftJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        $this->recordJoin('LEFT', $table, $column1, $column2, $alias);
        return $this->join('LEFT', $table, $column1, $column2, $alias);
    }

    public function rightJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        $this->recordJoin('RIGHT', $table, $column1, $column2, $alias);
        return $this->join('RIGHT', $table, $column1, $column2, $alias);
    }

    public function innerJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        $this->recordJoin('INNER', $table, $column1, $column2, $alias);
        return $this->join('INNER', $table, $column1, $column2, $alias);
    }

    public function getBindingParameters(): array
    {
        return $this->bindingValues;
    }

    public function queryString(): string
    {
        if (is_null($this->queryType)) {
            throw new Exception("No query type has been specified");
        }

        return "{$this->operationString}{$this->joinString}{$this->whereString}{$this->groupByString}";
    }

    private function buildOperationString(): string
    {
        $sqlOutput = '';

        switch ($this->queryType) {
            case QueryType::SELECT: {
                    $sqlOutput = 'SELECT ';

                    $selectCount = count($this->selects);
                    for ($index = 0; $index < $selectCount; $index++) {
                        $select = $this->selects[$index];
                        if ($select['type'] === 'column') {
                            $sqlOutput .= $select['columnName'];
                        }

                        if ($selectCount > 1 && $index < $selectCount - 1) {
                            $sqlOutput .= ', ';
                        }
                    }

                    $sqlOutput .= " FROM {$this->table}";

                    if ($this->alias !== null) {
                        $sqlOutput .= " AS {$this->alias}";
                    }

                    break;
                }
            case QueryType::INSERT: {
                    $keysSQL = '';
                    $valuesSQL = '';

                    $valueCount = count($this->inserts);

                    for ($index = 0; $index < $valueCount; $index++) {
                        $insert = $this->inserts[$index];
                        $bindingName = $this->createBindingParameterName();

                        $keysSQL .= $insert['key'];
                        $valuesSQL .= $bindingName;
                        $this->bindingValues[$bindingName] = $insert['value'];

                        if ($valueCount > 1 && $index < $valueCount - 1) {
                            $keysSQL .= ", ";
                            $valuesSQL .= ", ";
                        }
                    }

                    $sqlOutput = "INSERT INTO {$this->table} ({$keysSQL}) VALUES ({$valuesSQL})";
                    break;
                }
            case QueryType::DELETE: {
                    $sqlOutput = "DELETE FROM {$this->table}";
                    break;
                }
            default:
                throw new Exception("Invalid query type");
        }

        return $sqlOutput;
    }

    private function buildWhereString(array &$params): string
    {
        $sqlOutput = '';

        $whereCount = count($this->wheres);
        if ($whereCount > 0) {
            $sqlOutput .= ' WHERE ';
            $whereIndex = 0;

            foreach ($this->wheres as $where) {
                switch ($where['type']) {
                    case 'column': {
                            $generatedParamName = $this->createBindingParameterName();
                            $sqlOutput .= "{$where['key']} {$where['operator']} {$generatedParamName}";
                            $params[$generatedParamName] = $where['value'];

                            if ($whereIndex++ !== $whereCount - 1) {
                                $sqlOutput .= ' AND ';
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

    private function buildJoinString(): string
    {
        if (count($this->joins) === 0)
            return '';

        $sql = '';
        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']}";

            if ($join['alias'] !== null) {
                $sql .= " AS {$join['alias']}";
            }

            $sql .= " ON {$join['column1']} = {$join['column2']}";
        }

        return $sql;
    }


    private function buildGroupByString(): string
    {
        if (count($this->groupBys) === 0) {
            return '';
        }

        return ' GROUP BY '
            . implode(', ', $this->groupBys);
    }

    public function compileQuery(): CompiledQuery
    {
        if ($this->queryType === null) {
            throw new Exception('Attempting to compile query with no valid type');
        }

        $sqlOutput = '';
        $paramBindings = [];

        $sqlOutput .= $this->buildOperationString();
        $sqlOutput .= $this->buildJoinString();
        $sqlOutput .= $this->buildWhereString($paramBindings);
        $sqlOutput .= $this->buildGroupByString();

        return new CompiledQuery($sqlOutput, $paramBindings);
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
        //$queryString = $query->queryString();
        //$bindingParameters = $query->getBindingParameters();

        $compiledQuery = $query->compileQuery();

        $stmt = $this->pdo->prepare($compiledQuery->getSQL());
        $stmt->execute($compiledQuery->getBindings());
        return $stmt;
    }

    public function execute(QueryBuilder $query): bool
    {
        try {
            $this->executeImpl($query);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function executeAndReturnAll(QueryBuilder $query)
    {
        try {
            $stmt = $this->executeImpl($query);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function executeAndReturnOne(QueryBuilder $query)
    {
        try {
            $stmt = $this->executeImpl($query);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return [];
        }
    }
}
