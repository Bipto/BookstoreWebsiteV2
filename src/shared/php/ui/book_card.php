<?php

require_once '/var/www/shared/ui/carousel.php';

class BookCard implements Control
{
    public function __construct(private string $title, private string $author, private string $imagePath, private string $url, private float $price) {}

    public function render(): string
    {
        $displayImagePath = htmlspecialchars($this->imagePath);
        $displayTitle = htmlspecialchars($this->title);
        $displayAuthor = htmlspecialchars($this->author);
        $displayUrl = htmlspecialchars($this->url);
        $price = $this->price;
        $displayPrice = number_format($price, 2);

        return "
        <div class='card'>

            <img
                src='{$displayImagePath}'
                alt='{$displayTitle}'>

            <h3>
                {$displayTitle}
            </h3>

            <p>
                {$displayAuthor}
            </p>

            <p class='price'>
                £{$displayPrice}
            </p>

            <a href='{$displayUrl}'>
                View more
            </a>

        </div>";
    }
}
