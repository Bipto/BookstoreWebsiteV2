<?php

class Menubar
{
    public function __construct(private array $links, private int $cartCount) {}

    public function build()
    {

        $html = '<div class="menubar-container">';
        $html = '<div class="menubar">';

        // company logo section
        $html .= '<div class="company-logo">';
        $html .= '<img class="company-logo-img" src="/img/logo.png">';
        $html .= '</div>';

        //hamburger section
        $html .=
            '<button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>';

        //links
        $html .= '<div class="links" id="links">';

        //iterate through each link and add it to the output
        foreach ($this->links as $link) {
            foreach ($link as $name => $url) {
                $html .= '
                    <div class="menubar-link">
                        <a href="' . htmlspecialchars($url) . '">
                            <h2>' . $name . '</h2>
                        </a>
                    </div>';
            }
        }

        $html .= '</div>';

        $loggedIn = isset($_SESSION['email']);

        $styleText = $this->cartCount === 0 ? 'style="display: none"' : '';



        $html .= "
            <div class='right-hand-menu'>
            <div
            class='cart'
            x-data='cartMenu()'
            :class=\"{ 'open': cartOpen }\"
            @click.outside='cartOpen = false'
            @keydown.escape.window='cartOpen = false'
        >
            <div
                class='cart-circle'
                id='cart-circle'
                @click.stop='toggle()'
                role='button'
                tabindex='0'
                @keydown.enter.prevent='toggle()'
                @keydown.space.prevent='toggle()'
                :aria-expanded='cartOpen.toString()'
                aria-controls='cart-dropdown'
            >
                <i class='fa-solid fa-cart-shopping'></i>
    
                <span class='cart-count' id='cart-count' {$styleText}>{$this->cartCount}</span>
    
                <span class='dropdown-arrow-cart'></span>
            </div>
    
            <div
                class='menu-dropdown'
                id='cart-dropdown'
                style='width: 20em;'
                :class=\"{ 'show': cartOpen }\"
                x-show='cartOpen'
                x-cloak
            >
            <div
                id='cart-content'
                hx-get='/ajax/cart/get_cart.php'
                hx-trigger='cart-refresh'
                hx-target='this'
                hx-swap='innerHTML'
                hx-indicator='#cart-spinner'
                class='cart-content'
            >
            </div>

                <div
                    class='spinner-container htmx-indicator'
                    id='cart-spinner'
                >
                    <div class='spinner'></div>
                </div>
            </div>
        </div>";

        $html .= '<div class="profile">';

        if (!$loggedIn) {
            $html .= '
        <a href="/login" class="profile-button" id="profile-circle">
            <div class="profile-circle">
                <i class="fa-regular fa-user"></i>
            </div>
        </a>';
        } else {
            $html .= '
        <div class="profile-button" id="profile-circle">
            <div class="profile-circle">
                <i class="fa-regular fa-user"></i>
                <span class="dropdown-arrow-profile"></span>
            </div>
        </div>

        <div class="menu-dropdown" id="profile-dropdown">
            <a href="/profile">Profile</a>
            <a href="/settings">Settings</a>
            <a href="/logout">Log out</a>
        </div>';
        }

        $html .= '
    </div>';


        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= "<script>        
            function cartMenu() {
                return {
                    cartOpen: false,
            
                    toggle() {
                        this.cartOpen = !this.cartOpen;
            
                        if (this.cartOpen) {
                            htmx.trigger(
                                document.getElementById('cart-content'),
                                'cart-refresh'
                            );
                        }
                    }
                };
            }        
        </script>";

        return $html;
    }
}
