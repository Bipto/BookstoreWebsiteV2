<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db.php';

class AuthorController
{
    public function __construct(private RelationalDatabase $db) {}

    public function getAuthors(): void
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $parameters = [];
        parse_str($queryString, $parameters);

        $title = $parameters['title'] ?? '';
        $author = $parameters['author'] ?? '';
        $genres = $parameters['genre'] ?? [];
        $limit = $parameters['limit'] ?? null;
        $offset = $parameters['offset'] ?? null;

        $query = QueryBuilder::table('bookstore.authors', 'a')
            ->select(
                new ColumnName("a.name"),
                new ColumnName("a.image_path")
            );

        if ($limit !== null) {
            $query->limit($limit);
        }

        if ($offset !== null) {
            $query->offset($offset);
        }

        try {
            $data = $this->db->executeAndReturnAll($query);
            REST::response(200, $data);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getBookById(int $id): void
    {
        $query = QueryBuilder::table('bookstore.books', 'b')
            ->select(
                new ColumnName('b.book_id'),
                new ColumnName('b.title'),
                new ColumnName('a.name', 'author'),
                new ColumnName('b.book_description'),
                new ColumnName('b.price'),
                new ColumnName('b.stock_count'),
                new ColumnName('b.image_path'),
                new ColumnName("COALESCE(
                        json_agg(
                                json_build_object(
                                    'genre_id', g.genre_id,
                                    'name', g.name
                                )
                                ORDER BY g.name
                            ) FILTER (WHERE g.name IS NOT NULL),
                            '[]'::json
                        ) AS genres")
            )
            ->innerJoin('bookstore.authors', 'b.author_id', 'a.author_id', 'a')
            ->leftJoin('bookstore.book_genres', 'b.book_id ', 'bg.book_id', 'bg')
            ->leftJoin('bookstore.genres', 'bg.genre_id ', 'g.genre_id', 'g')
            ->whereEqual('b.book_id', new Constant($id))
            ->groupBy([
                'b.book_id',
                'b.title',
                'a.name',
                'b.book_description',
                'b.price',
                'b.stock_count',
                'b.image_path'
            ]);

        try {
            $result = $this->db->executeAndReturnOne($query);
            REST::response(200, $result);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }
}
