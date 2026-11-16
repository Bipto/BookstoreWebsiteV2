<?php

require_once '/var/www/shared/db/db.php';
require_once '/var/www/shared/db/postgres_compiler.php';

class CartController
{
    public function __construct(private RelationalDatabase $db) {}

    public function getCart(): void
    {
        $email = $_GET['email'] ?? null;

        $query = QueryBuilder::table('bookstore.books', 'b')
            ->select(
                new ColumnName('cart_item_id'),
                new ColumnName('title'),
                new ColumnName('a.name AS author'),
                new ColumnName('price'),
                new ColumnName('b.image_path'),
                new ColumnName('quantity')
            )
            ->innerJoin('bookstore.cart_items', 'ci.book_id', 'b.book_id', 'ci')
            ->innerJoin('bookstore.users', 'u.user_id', 'ci.user_id', 'u')
            ->innerJoin('bookstore.authors', 'a.author_id', 'b.author_id', 'a')
            ->whereEqual('email', new Constant($email));

        try {
            $data = $this->db->executeAndReturnAll(
                new PostgresCompiler(),
                $query
            );
            REST::response(200, $data);
        } catch (Exception $e) {
            REST::response(500, ['error' => 'An error occurred accessing te database']);
        }
    }

    public function removeItem(): void
    {
        $id = $_GET['id'];

        $query = QueryBuilder::table('bookstore.cart_items')
            ->delete()
            ->whereEqual('cart_item_id', new Constant($id));

        try {
            $this->db->execute(
                new PostgresCompiler(),
                $query
            );
            REST::response(200, []);
        } catch (Exception $e) {
            REST::response(500, ['error' => 'An error occurred processing the request']);
        }
    }

    public function addBook(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'];
        $bookId = $data['bookId'];
        $quantity = $data['quantity'] ?? 1;

        $selectQuery = QueryBuilder::table('bookstore.users')
            ->select(new ColumnName('user_id'))
            ->whereEqual('email', new Constant($email));

        try {
            $data = $this->db->executeAndReturnOne(
                new PostgresCompiler(),
                $selectQuery
            );

            $userId = $data['user_id'] ?? null;

            $insertQuery = QueryBuilder::table('bookstore.cart_items')
                ->insert([
                    'user_id' => $userId,
                    'book_id' => $bookId,
                    'quantity' => $quantity
                ]);
            $this->db->execute(new PostgresCompiler(), $insertQuery);

            REST::response(200, []);
        } catch (Exception $e) {
            REST::response(500, ['error' => 'An error occurred processing the request']);
        }
    }
}
