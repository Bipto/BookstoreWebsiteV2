<?php

function truncate(string $text, int $maxLength): string
{
    if (strlen($text) <= $maxLength) {
        return $text;
    }

    $text = substr($text, 0, $maxLength - 3);
    $text = substr($text, 0, strrpos($text, ' '));

    return $text . '...';
}

function decodeJsonObject(mixed $data, ?bool $associative = null): mixed
{
    if (is_string($data)) {
        $trimmed = trim($data);

        if (
            str_starts_with($trimmed, '{') ||
            str_starts_with($trimmed, '[')
        ) {
            $decoded = json_decode($trimmed);

            if (json_last_error() === JSON_ERROR_NONE) {
                return decodeJsonObject($decoded, $associative);
            }
        }

        return $data;
    }

    if (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = decodeJsonObject($value, $associative);
        }
    } elseif (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = decodeJsonObject($value, $associative);
        }
    }

    return $data;
}
