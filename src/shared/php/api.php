<?php

require_once '/var/www/shared/utils/text_utils.php';

class API_Response
{
    private function __construct(private bool $success, private ?string $errorMessage, private ?array $data, private int $responseCode) {}

    public static function error(string $errorMessage, int $responseCode): API_Response
    {
        return new API_Response(false, $errorMessage, null, $responseCode);
    }

    public static function success(array $data, int $responseCode): API_Response
    {
        return new API_Response(true, null, $data, $responseCode);
    }

    public function hasError(): bool
    {
        return !$this->success;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}

class API
{
    private static function send(string $url, array $options): API_Response
    {
        $json = [];
        $ch = curl_init("http://apache{$url}");

        curl_setopt_array($ch, $options);

        try {
            $response = curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                //$json['error'] = curl_error($ch);
                //$json['success'] = false;

                return API_Response::error(curl_error($ch), 0);
            } else {
                //$json['success'] = ($httpCode >= 200 && $httpCode < 300);
                //$json['status_code'] = $httpCode;
                //$json['data'] = $response;

                return $httpCode >= 200 && $httpCode < 300 ?
                    API_Response::success(json_decode($response, true), $httpCode) :
                    API_Response::error(curl_error($ch), $httpCode);
            }
        } catch (Exception $e) {
            return API_Response::error($e->getMessage(), 0);
        }
    }

    public static function get(string $url): API_Response
    {
        return API::send($url, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);
    }

    public static function post(string $url, array $data = []): API_Response
    {
        return API::send($url, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);
    }

    public static function put(string $url, array $data = []): API_Response
    {
        return API::send($url, [
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);
    }


    public static function delete(string $url, array $data = []): API_Response
    {
        return API::send($url, [
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Host: api.localhost',
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);
    }
}
