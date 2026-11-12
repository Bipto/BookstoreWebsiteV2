<?php

require_once '/var/www/shared/db/db.php';

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
