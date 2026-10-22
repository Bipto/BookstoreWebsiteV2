<?php

class Menubar
{
    public function __construct(
        private array $links,
        private int $cartCount
    ) {}

    public function build(): string
    {
        $html = '<div class="menubar-container">';

        // Shared Alpine.js state for the entire menubar
        $html .= '
            <div
                class="menubar"
                x-data="menuState()"
                @keydown.escape.window="
                    hamburgerOpen = false;
                    cartOpen = false;
                    profileOpen = false;
                "
            >';

        // Company logo
        $html .= '
            <div class="company-logo">
                <a href="/" aria-label="Home">
                    <img
                        class="company-logo-img"
                        src="/img/logo.png"
                        alt="Company logo"
                    >
                </a>
            </div>';

        // Hamburger button
        $html .= '
            <button
                class="hamburger"
                id="hamburger"
                type="button"
                :class="{ \'open\': hamburgerOpen }"
                @click="hamburgerOpen = !hamburgerOpen"
                :aria-expanded="hamburgerOpen.toString()"
                :aria-label="
                    hamburgerOpen
                        ? \'Close navigation menu\'
                        : \'Open navigation menu\'
                "
                aria-controls="links"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>';

        // Navigation links — use the existing PHP data
        $html .= '
            <nav
                class="links"
                id="links"
                aria-label="Main navigation"
                :class="{ \'show\': hamburgerOpen }"
            >';

        foreach ($this->links as $link) {
            foreach ($link as $name => $url) {
                $safeName = htmlspecialchars(
                    (string) $name,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );

                $safeUrl = htmlspecialchars(
                    (string) $url,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );

                $html .= '
                    <div class="menubar-link">
                        <a href="' . $safeUrl . '">
                            <h2>' . $safeName . '</h2>
                        </a>
                    </div>';
            }
        }

        $html .= '</nav>';

        // Authentication state
        $loggedIn = isset($_SESSION['email']);

        // Hide cart count when zero
        $styleText = $this->cartCount === 0
            ? 'style="display: none"'
            : '';

        // Right-hand menu
        $html .= '
            <div class="right-hand-menu">';

        // Cart
        $html .= "
            <div
                class='cart'
                :class=\"{ 'open': cartOpen }\"
                @click.outside='cartOpen = false'
            >
                <div
                    class='cart-circle'
                    id='cart-circle'
                    @click.stop=\"
                        cartOpen = !cartOpen;
                        profileOpen = false;

                        if (cartOpen) {
                            htmx.trigger(
                                document.getElementById('cart-content'),
                                'cart-refresh'
                            );
                        }
                    \"
                    role='button'
                    tabindex='0'
                    @keydown.enter.prevent=\"
                        cartOpen = !cartOpen;
                        profileOpen = false;

                        if (cartOpen) {
                            htmx.trigger(
                                document.getElementById('cart-content'),
                                'cart-refresh'
                            );
                        }
                    \"
                    @keydown.space.prevent=\"
                        cartOpen = !cartOpen;
                        profileOpen = false;

                        if (cartOpen) {
                            htmx.trigger(
                                document.getElementById('cart-content'),
                                'cart-refresh'
                            );
                        }
                    \"
                    :aria-expanded='cartOpen.toString()'
                    aria-controls='cart-dropdown'
                >
                    <i class='fa-solid fa-cart-shopping'></i>

                    <span
                        class='cart-count'
                        id='cart-count'
                        {$styleText}
                    >{$this->cartCount}</span>

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
                    ></div>

                    <div
                        class='spinner-container htmx-indicator'
                        id='cart-spinner'
                    >
                        <div class='spinner'></div>
                    </div>
                </div>
            </div>";

        // Profile
        $html .= '
            <div
                class="profile"
                @click.outside="profileOpen = false"
            >';

        if (!$loggedIn) {
            // Logged-out user
            $html .= '
                <a
                    href="/login"
                    class="profile-button"
                    id="profile-circle"
                    aria-label="Log in"
                >
                    <div class="profile-circle">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </a>';
        } else {
            // Logged-in user: preserve original div styling
            $html .= '
                <div
                    class="profile-button"
                    id="profile-circle"
                    role="button"
                    tabindex="0"
                    @click="
                        profileOpen = !profileOpen;
                        cartOpen = false;
                    "
                    @keydown.enter.prevent="
                        profileOpen = !profileOpen;
                        cartOpen = false;
                    "
                    @keydown.space.prevent="
                        profileOpen = !profileOpen;
                        cartOpen = false;
                    "
                    :aria-expanded="profileOpen.toString()"
                    aria-controls="profile-dropdown"
                    aria-label="Open profile menu"
                >
                    <div class="profile-circle">
                        <i class="fa-regular fa-user"></i>

                        <span
                            class="dropdown-arrow-profile"
                            :class="{ \'rotated\': profileOpen }"
                        ></span>
                    </div>
                </div>

                <div
                    class="menu-dropdown"
                    id="profile-dropdown"
                    :class="{ \'show\': profileOpen }"
                    x-show="profileOpen"
                    x-cloak
                >
                    <a href="/profile">Profile</a>
                    <a href="/settings">Settings</a>
                    <a href="/logout">Log out</a>
                </div>';
        }

        $html .= '</div>'; // Profile
        $html .= '</div>'; // Right-hand menu
        $html .= '</div>'; // Menubar
        $html .= '</div>'; // Menubar container

        return $html;
    }
}

// Alpine.js menu state
?>
<script>
    function menuState() {
        return {
            hamburgerOpen: false,
            cartOpen: false,
            profileOpen: false
        };
    }
</script>