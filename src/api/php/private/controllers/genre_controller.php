<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db/db.php';
require_once '/var/www/shared/db/postgres_compiler.php';

class GenreController
{
    public function __construct(private RelationalDatabase $db) {}

    public function getGenres(): void
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $parameters = [];
        parse_str($queryString, $parameters);

        $active = $parameters['active'] ?? false;
        $limit = $parameters['limit'] ?? null;
        $offset = $parameters['offset'] ?? null;

        $query = QueryBuilder::table('bookstore.genres', 'g')
            ->select(
                new ColumnName('g.genre_id'),
                new ColumnName('g.name'),
                new ColumnCount('bg.book_id', 'book_count')
            )
            ->groupBy([
                'g.genre_id',
                'g.name'
            ]);

        $active ?
            $query->innerJoin('bookstore.book_genres', 'g.genre_id', 'bg.genre_id', 'bg') :
            $query->leftJoin('bookstore.book_genres', 'g.genre_id', 'bg.genre_id', 'bg');

        if ($limit !== null) {
            $query->limit($limit);
        }

        if ($offset !== null) {
            $query->offset($offset);
        }

        try {
            $data = $this->db->executeAndReturnAll(
                new PostgresCompiler(),
                $query
            );

            REST::response(200, $data);
        } catch (Exception $e) {
            REST::response(500, ['error' => 'An error occurred accessing the database']);
        }
    }

    public function getGenreById(int $id): void
    {
        $queryBuilder = QueryBuilder::table('bookstore.genres', 'g')
            ->select(
                new ColumnName('genre_id'),
                new ColumnName('name'),
                new ColumnName('description')
            )
            ->whereEqual('genre_id', new Constant($id));

        $results = $this->db->executeAndReturnOne(
            new PostgresCompiler(),
            $queryBuilder
        );

        try {
            if ($results === false) {
                REST::response(404, ['error' => 'An invalid genre ID was entered']);
            } else {
                REST::response(200, $results);
            }
        } catch (Exception $e) {
            REST::response(500, ['error' => 'An error occurred accessing the database']);
        }
    }
}
