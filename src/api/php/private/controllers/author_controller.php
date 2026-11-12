<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db/db.php';
require_once '/var/www/shared/db/postgres_compiler.php';

class AuthorController
{
    public function __construct(private RelationalDatabase $db) {}

    private function queryAuthors(?int $limit, ?int $offset, ?int $id): QueryBuilder
    {
        $query = QueryBuilder::table('bookstore.authors', 'a')
            ->select(
                new ColumnName("a.author_id"),
                new ColumnName("a.name"),
                new ColumnName("a.description"),
                new ColumnName("a.image_path")
            );

        if ($id !== null) {
            $query->whereEqual('a.author_id', new Constant($id));
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        if ($offset !== null) {
            $query->offset($offset);
        }

        return $query;
    }

    public function getAuthors(): void
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $parameters = [];
        parse_str($queryString, $parameters);

        $limit = $parameters['limit'] ?? null;
        $offset = $parameters['offset'] ?? null;

        $query = $this->queryAuthors($limit, $offset, null);

        try {
            $data = $this->db->executeAndReturnAll(
                new PostgresCompiler(),
                $query
            );

            REST::response(200, $data);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getAuthorById(int $id): void
    {
        $query = $this->queryAuthors(null, null, $id);

        try {
            $data = $this->db->executeAndReturnOne(
                new PostgresCompiler(),
                $query
            );

            REST::response(200, $data);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }
}
