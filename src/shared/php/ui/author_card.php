<?php

require_once '/var/www/shared/ui/carousel.php';

class AuthorCard implements Control
{
    public function __construct(private string $name, private string $imagePath, private string $url) {}

    public function render(): string
    {
        $displayImagePath = htmlspecialchars($this->imagePath);
        $displayName = htmlspecialchars($this->name);
        $displayUrl = htmlspecialchars($this->url);

        return "
        <div class='card'>

            <img
                src='{$displayImagePath}'
                alt='{$displayName}'>

            <h3>
                {$displayName}
            </h3>

            <a href='{$displayUrl}'>
                View more
            </a>

        </div>";
    }
}
