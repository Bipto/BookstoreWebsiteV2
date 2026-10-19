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
