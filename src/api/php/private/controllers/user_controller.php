<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db/db.php';
require_once '/var/www/shared/db/postgres_compiler.php';

class UserController
{
    public function __construct(private RelationalDatabase $db) {}

    public function addUser(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $queryBuilder = QueryBuilder::table('bookstore.users')
            ->insert([
                'email' => $data['email'],
                'first_name' =>  $data['firstName'],
                'last_name' => $data['lastName'],
                'password' => $password
            ]);

        try {
            $this->db->execute(
                new PostgresCompiler(),
                $queryBuilder
            );
            REST::response(201, []);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getUsers(): void
    {
        $query = QueryBuilder::table('bookstore.users', 'u')
            ->select(
                new ColumnName("u.user_id"),
                new ColumnName("u.email"),
                new ColumnName("u.first_name"),
                new ColumnName("u.last_name")
            );

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
}
