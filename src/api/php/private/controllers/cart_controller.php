<?php

require_once '/var/www/shared/db.php';

class CartController
{
    public function __construct(private RelationalDatabase $db) {}

    public function getCart(): void
    {
        $email = $_GET['email'] ?? null;

        $query = QueryBuilder::table('bookstore.books', 'b')
            ->select(
                [
                    'cart_item_id',
                    'title',
                    'a.name AS author',
                    'price',
                    'b.image_path',
                    'quantity'
                ]
            )
            ->innerJoin('bookstore.cart_items', 'ci.book_id', 'b.book_id', 'ci')
            ->innerJoin('bookstore.carts', 'c.cart_id', 'ci.cart_id', 'c')
            ->innerJoin('bookstore.users', 'u.user_id', 'c.user_id', 'u')
            ->innerJoin('bookstore.authors', 'a.author_id', 'b.author_id', 'a')
            ->whereEqual('email', $email);

        $data = $this->db->executeAndReturnAll($query);
        $json = [];
        $json['success'] = true;
        $json['cart'] = $data;

        echo json_encode($json);
    }

    public function removeItem(): void
    {
        $id = $_GET['id'];

        $query = QueryBuilder::table('bookstore.cart_items')
            ->delete()
            ->whereEqual('cart_item_id', $id);

        $json = [];
        $json['success'] = $this->db->execute($query);

        echo json_encode($json);
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
            WITH inserted AS (
                INSERT INTO bookstore.carts (user_id)
                SELECT :user_id
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM bookstore.carts
                    WHERE user_id = :user_id
                )
                RETURNING cart_id
            )
            SELECT cart_id FROM inserted
            UNION ALL
            SELECT cart_id
            FROM bookstore.carts
            WHERE user_id = :user_id
            LIMIT 1;
        ');
        $succeeded = $stmt->execute(['user_id' => $userId]);
        if (!$succeeded) {
            echo json_encode(['success' => false]);
            return;
        }

        $cartId = $stmt->fetch(PDO::FETCH_ASSOC)['cart_id'] ?? null;

        $stmt = $pdo->prepare('
        INSERT INTO bookstore.cart_items(cart_id, book_id, quantity)
        VALUES(:cart_id, :book_id, :quantity)
        ');
        $succeeded = $stmt->execute([
            ':cart_id' => $cartId,
            ':book_id' => $bookId,
            ':quantity' => $quantity
        ]);

        echo json_encode(['success' => $succeeded]);
    }
}
