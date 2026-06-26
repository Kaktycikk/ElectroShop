<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 sidebar">

    <h4 class="sidebar-logo">

        <i class="bi bi-cpu-fill me-2"></i>

        ElectroAdmin

    </h4>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="dashboard.php"
            class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="bi bi-house-door me-2"></i>
                Главная
            </a>
        </li>

        <li class="nav-item">
            <a href="products.php"
            class="nav-link <?= ($currentPage == 'products.php') ? 'active' : '' ?>">
                <i class="bi bi-box-seam me-2"></i>
                Товары
            </a>
        </li>

        <li class="nav-item">
            <a href="categories.php"
            class="nav-link <?= ($currentPage == 'categories.php') ? 'active' : '' ?>">
                <i class="bi bi-grid me-2"></i>
                Категории
            </a>
        </li>

        <li class="nav-item">
            <a href="users.php"
            class="nav-link <?= ($currentPage == 'users.php') ? 'active' : '' ?>">
                <i class="bi bi-people me-2"></i>
                Пользователи
            </a>
        </li>

        <li class="nav-item">
            <a href="orders.php"
            class="nav-link <?= ($currentPage == 'orders.php') ? 'active' : '' ?>">
                <i class="bi bi-bag-check me-2"></i>
                Заказы
            </a>
        </li>

    </ul>

    <hr class="my-4">

        <div class="admin-box">

            <div class="admin-avatar">

                <i class="bi bi-person-circle"></i>

            </div>

            <div>

                <div class="admin-name">

                    <?= htmlspecialchars($_SESSION["user_name"] ?? "Администратор") ?>

                </div>

                <div class="admin-role">

                    Администратор

                </div>

            </div>

        </div>

        <a href="/logout.php"
        class="btn btn-logout w-100">

            <i class="bi bi-box-arrow-right me-2"></i>

            Выйти

        </a>

</div>