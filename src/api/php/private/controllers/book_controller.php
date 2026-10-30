<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db.php';

class BookController
{
    public function __construct(private RelationalDatabase $db) {}

    public function getBooks(): void
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $parameters = [];
        parse_str($queryString, $parameters);

        $genres = $parameters['genre'] ?? [];

        $pdo = $this->db->getPDO();

        $sql = "
        SELECT
            b.book_id,
            b.title,
            a.name AS author,
            b.book_description,
            b.price,
            b.stock_count,
            b.image_path,
            COALESCE(
                json_agg(
                    json_build_object(
                        'genre_id', g.genre_id,
                        'name', g.name
                    )
                    ORDER BY g.name
                ) FILTER (WHERE g.name IS NOT NULL),
                '[]'::json
            ) AS genres
        FROM bookstore.books AS b
        INNER JOIN bookstore.authors AS a
            ON b.author_id = a.author_id
        LEFT JOIN bookstore.book_genres AS bg
            ON b.book_id = bg.book_id
        LEFT JOIN bookstore.genres AS g
            ON bg.genre_id = g.genre_id
        WHERE b.title ILIKE :title
        AND a.name ILIKE :author
    ";

        $params = [
            'title' => '%' . ($parameters['title'] ?? '') . '%',
            'author' => '%' . ($parameters['author'] ?? '') . '%',
        ];

        if (!empty($genres)) {
            $placeholders = [];

            foreach (array_values($genres) as $i => $genreId) {
                $key = "genre_$i";
                $placeholders[] = ":$key";
                $params[$key] = (int) $genreId;
            }

            $sql .= "
        AND EXISTS (
            SELECT 1
            FROM bookstore.book_genres AS bg2
            WHERE bg2.book_id = b.book_id
              AND bg2.genre_id IN (" . implode(', ', $placeholders) . ")
        )
    ";
        }

        $sql .= "
        GROUP BY
            b.book_id,
            b.title,
            a.name,
            b.book_description,
            b.price,
            b.stock_count,
            b.image_path
    ";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll();
            REST::response(200, $data);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getBookById(int $id): void
    {
        $query = QueryBuilder::table('bookstore.books', 'b')
            ->select([
                'b.book_id',
                'b.title',
                'a.name AS author',
                'b.book_description',
                'b.price',
                'b.stock_count',
                'b.image_path',
                "COALESCE(
                        json_agg(
                                json_build_object(
                                    'genre_id', g.genre_id,
                                    'name', g.name
                                )
                                ORDER BY g.name
                            ) FILTER (WHERE g.name IS NOT NULL),
                            '[]'::json
                        ) AS genres"
            ])
            ->innerJoin('bookstore.authors', 'b.author_id', 'a.author_id', 'a')
            ->leftJoin('bookstore.book_genres', 'b.book_id ', 'bg.book_id', 'bg')
            ->leftJoin('bookstore.genres', 'bg.genre_id ', 'g.genre_id', 'g')
            ->whereEqual('b.book_id', $id)
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
