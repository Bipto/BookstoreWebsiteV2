<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/db.php';

class AuthController
{
    public function __construct(private RelationalDatabase $db) {}

    public function handleLogin(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $queryBuilder = QueryBuilder::table('bookstore.users')
                ->select()
                ->where([
                    'email' => $data['email']
                ]);

            $compiledQuery = $queryBuilder->compileQuery();

            $pdo = $this->db->getPDO();
            $stmt = $pdo->prepare($compiledQuery->getSQL());
            $stmt->execute($compiledQuery->getBindings());
            $results = $stmt->fetch();

            //$results = $this->db->executeAndReturnOne($queryBuilder);
            $password = $data['password'];

            if ($password && password_verify($password, $results['password'])) {
                REST::response(200, []);
            } else if (!$password) {
                REST::response(401, ['error' => 'An account could not be found with the provided email']);
            } else {
                REST::response(401, ['error' => 'The provided details are incorrect']);
            }
        } catch (Exception $e) {
            REST::response(500, ['error' => 'An error occurred accessing the database']);
        }
    }
}
