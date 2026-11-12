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

        $pdo = $this->db->getPDO();

        $stmt = $pdo->prepare('
            SELECT user_id FROM bookstore.users
            WHERE email = :email
        ');

        $succeeded = $stmt->execute(['email' => $email]);
        if (!$succeeded) {
            echo json_encode(['success' => false]);
            return;
        }

        $userId = $succeeded ? $stmt->fetch(PDO::FETCH_ASSOC)['user_id'] : null;

        $stmt = $pdo->prepare('
        INSERT INTO bookstore.cart_items(user_id, book_id, quantity)
        VALUES(:user_id, :book_id, :quantity)
        ');
        $succeeded = $stmt->execute([
            ':user_id' => $userId,
            ':book_id' => $bookId,
            ':quantity' => $quantity
        ]);

        echo json_encode(['success' => $succeeded]);
    }
}
