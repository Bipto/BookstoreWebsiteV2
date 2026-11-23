<?php

class BookstoreSidebar
{
    private array $menu;
    private string $currentPage;

    public function __construct()
    {
        $this->currentPage = basename(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        $this->menu = [
            [
                "title" => "Dashboard",
                "icon" => "fa-solid fa-chart-line",
                "link" => "/"
            ],
            [
                "title" => "Users",
                "icon" => "fa-solid fa-users",
                "children" => [
                    ["title" => "All Users", "link" => "/users"],
                    ["title" => "Add User", "link" => "/add-user"],
                    ["title" => "Customers", "link" => "/customers"],
                    ["title" => "Staff", "link" => "/staff"]
                ]
            ],
            [
                "title" => "Books",
                "icon" => "fa-solid fa-book-open",
                "children" => [
                    ["title" => "All Books", "link" => "/books"],
                    ["title" => "Add Book", "link" => "/add-book"],
                    ["title" => "Stock Management", "link" => "/stock"]
                ]
            ],
            [
                "title" => "Authors",
                "icon" => "fa-solid fa-pen-nib",
                "children" => [
                    ["title" => "All Authors", "link" => "/authors"],
                    ["title" => "Add Author", "link" => "/add-author"]
                ]
            ],
            [
                "title" => "Genres",
                "icon" => "fa-solid fa-tags",
                "children" => [
                    ["title" => "All Genres", "link" => "/genres"],
                    ["title" => "Add Genre", "link" => "/add-genre"]
                ]
            ],
            [
                "title" => "Reports",
                "icon" => "fa-solid fa-chart-pie",
                "children" => [
                    ["title" => "Sales Reports", "link" => "/sales-reports"],
                    ["title" => "Stock Reports", "link" => "/stock-reports"],
                    ["title" => "Customer Reports", "link" => "/customer-reports"]
                ]
            ]
        ];
    }

    /**
     * Escape HTML output.
     */
    private function escape(string $value): string
    {
        return htmlspecialchars(
            $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }

    /**
     * Check whether a menu link matches the current page.
     */
    private function isCurrentPage(string $link): bool
    {
        $linkPath = trim(
            parse_url($link, PHP_URL_PATH),
            '/'
        );

        return $linkPath === $this->currentPage
            || basename($linkPath) === $this->currentPage;
    }

    /**
     * Check whether a menu section contains the current page.
     */
    private function isMenuActive(array $item): bool
    {
        if (isset($item['link'])) {
            return $this->isCurrentPage($item['link']);
        }

        foreach ($item['children'] ?? [] as $child) {
            if ($this->isCurrentPage($child['link'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render the sidebar.
     */
    public function render(): void
    {
?>

        <div
            x-data="{
                collapsed: false,
                openMenus: {}
            }"
            class="admin-layout">

            <aside
                id="sidebar"
                class="sidebar"
                :class="{ 'collapsed': collapsed }">

                <div class="sidebar-header">

                    <a href="http://localhost">
                        <h2 class="menu-title">
                            Selby Bookstore
                        </h2>
                    </a>

                    <button
                        type="button"
                        class="toggle-btn"
                        @click="collapsed = !collapsed"
                        :aria-expanded="!collapsed"
                        aria-label="Toggle sidebar">

                        <i class="fa-solid fa-bars"></i>

                    </button>

                </div>

                <nav>

                    <?php foreach ($this->menu as $index => $item): ?>

                        <?php
                        $isActive = $this->isMenuActive($item);
                        $menuKey = 'menu' . $index;
                        ?>

                        <?php if (isset($item['children'])): ?>

                            <div class="menu-group">

                                <button
                                    type="button"
                                    class="menu-item"
                                    :class="{
                                        'active': <?= $isActive ? 'true' : 'false' ?> || openMenus['<?= $menuKey ?>']
                                    }"
                                    @click="
                                        if (collapsed) {
                                            collapsed = false;
                                            openMenus['<?= $menuKey ?>'] = true;
                                        } else {
                                            openMenus['<?= $menuKey ?>'] = !openMenus['<?= $menuKey ?>'];
                                        }
                                    "
                                    title="<?= $this->escape($item['title']) ?>">

                                    <span class="menu-label">

                                        <span class="menu-icon">
                                            <i class="<?= $this->escape($item['icon']) ?>" aria-hidden="true"></i>
                                        </span>

                                        <span class="menu-text">
                                            <?= $this->escape($item['title']) ?>
                                        </span>

                                    </span>

                                    <span
                                        class="arrow"
                                        x-show="!collapsed"
                                        :class="{
                                            'rotated': openMenus['<?= $menuKey ?>'] || <?= $isActive ? 'true' : 'false' ?>
                                        }">

                                        <i class="fa-solid fa-chevron-down"></i>

                                    </span>

                                </button>

                                <div
                                    class="submenu"
                                    x-show="!collapsed && (openMenus['<?= $menuKey ?>'] || <?= $isActive ? 'true' : 'false' ?>)"
                                    x-cloak>

                                    <?php foreach ($item['children'] as $child): ?>

                                        <?php $childActive = $this->isCurrentPage($child['link']); ?>

                                        <a
                                            href="<?= $this->escape($child['link']) ?>"
                                            class="<?= $childActive ? 'active' : '' ?>"
                                            <?= $childActive ? 'aria-current="page"' : '' ?>>

                                            <?= $this->escape($child['title']) ?>

                                        </a>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php else: ?>

                            <a
                                class="menu-item <?= $isActive ? 'active' : '' ?>"
                                href="<?= $this->escape($item['link']) ?>"
                                title="<?= $this->escape($item['title']) ?>"
                                <?= $isActive ? 'aria-current="page"' : '' ?>>

                                <span class="menu-label">

                                    <span class="menu-icon">
                                        <i class="<?= $this->escape($item['icon']) ?>" aria-hidden="true"></i>
                                    </span>

                                    <span class="menu-text">
                                        <?= $this->escape($item['title']) ?>
                                    </span>

                                </span>

                            </a>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </nav>

            </aside>

            <main
                id="content"
                class="content"
                :class="{ 'expanded': collapsed }">

            <?php
        }

        /**
         * Close the sidebar layout.
         */
        public function close(): void
        {
            ?>

            </main>

        </div>

<?php
        }
    }
