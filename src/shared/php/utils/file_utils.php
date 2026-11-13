<?php

function getImagePath(string $filename): string
{
    return "http://assets.localhost/img/{$filename}";
}

function getBookImagePath(string $filename): string
{
    return "http://assets.localhost/img/books/{$filename}";
}

function getAuthorImagePath(string $filename): string
{
    return "http://assets.localhost/img/authors/{$filename}";
}

function sanitiseImageUrl(string $url): string
{
    return htmlspecialchars($url);
}
