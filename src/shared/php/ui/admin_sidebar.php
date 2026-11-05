<?php

class BookstoreSidebar
{
    private array $menu;
    private string $currentPage;

    public function __construct()
    {
        $this->currentPage = basename($_SERVER['PHP_SELF']);

        $this->menu = [
            [
                "title" => "Dashboard",
                "icon" => "📊",
                "link" => "dashboard.php"
            ],
            [
                "title" => "Users",
                "icon" => "👥",
                "children" => [
                    ["title" => "All Users", "link" => "/users"],
                    ["title" => "Add User", "link" => "/add-user"],
                    ["title" => "Customers", "link" => "/customers"],
                    ["title" => "Staff", "link" => "/staff"]
                ]
            ],
            [
                "title" => "Books",
                "icon" => "📚",
                "children" => [
                    ["title" => "All Books", "link" => "/books"],
                    ["title" => "Add Book", "link" => "/add-book"],
                    ["title" => "Edit Books", "link" => "/edit-books"],
                    ["title" => "Stock Management", "link" => "/stock"]
                ]
            ],
            [
                "title" => "Reports",
                "icon" => "📈",
                "children" => [
                    ["title" => "Sales Reports", "link" => "/sales-reports"],
                    ["title" => "Stock Reports", "link" => "/stock-reports"],
                    ["title" => "Customer Reports", "link" => "/customer-reports"]
                ]
            ],
            [
                "title" => "Genres",
                "icon" => "🏷️",
                "children" => [
                    ["title" => "All Genres", "link" => "/genres"],
                    ["title" => "Add Genre", "link" => "/add-genre"],
                    ["title" => "Edit Genres", "link" => "/edit-genres"]
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
     * Check whether a menu section contains the current page.
     */
    private function isMenuActive(array $item): bool
    {
        if (isset($item['link'])) {
            return $item['link'] === $this->currentPage;
        }

        foreach ($item['children'] ?? [] as $child) {
            if ($child['link'] === $this->currentPage) {
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
                        ☰
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
                                            <?= $item['icon'] ?>
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
                                        ▼
                                    </span>

                                </button>

                                <div
                                    class="submenu"
                                    x-show="!collapsed && (openMenus['<?= $menuKey ?>'] || <?= $isActive ? 'true' : 'false' ?>)"
                                    x-cloak>

                                    <?php foreach ($item['children'] as $child): ?>

                                        <a
                                            href="<?= $this->escape($child['link']) ?>"
                                            class="<?= $child['link'] === $this->currentPage ? 'active' : '' ?>"
                                            <?= $child['link'] === $this->currentPage ? 'aria-current="page"' : '' ?>>
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
                                        <?= $item['icon'] ?>
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

        public function close(): void
        {
            ?>
            </main>

        </div>
<?php
        }
    }
