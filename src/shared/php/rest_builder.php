<?php

class REST
{
    public static function response(int $responseCode, array $data)
    {
        http_response_code($responseCode);

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        echo json_encode(
            $data,
            JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE |
                JSON_THROW_ON_ERROR
        );

        exit;
    }
}
