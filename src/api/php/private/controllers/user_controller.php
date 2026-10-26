<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db.php';

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
            $json = [];
            $json['success'] = $this->db->execute($queryBuilder);
            echo json_encode($json);

            REST::response(201, []);
        } catch (Exception $e) {
            REST::response(500, ['error' => $e->getMessage()]);
        }
    }
}
