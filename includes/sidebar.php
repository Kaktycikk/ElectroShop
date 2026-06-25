<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 sidebar">

    <h4>ElectroAdmin</h4>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="dashboard.php"
               class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                Главная
            </a>
        </li>

        <li class="nav-item">
            <a href="products.php"
               class="nav-link <?= ($currentPage == 'products.php') ? 'active' : '' ?>">
                Товары
            </a>
        </li>

        <li class="nav-item">
            <a href="categories.php"
               class="nav-link <?= ($currentPage == 'categories.php') ? 'active' : '' ?>">
                Категории
            </a>
        </li>

        <li class="nav-item">
            <a href="users.php"
               class="nav-link <?= ($currentPage == 'users.php') ? 'active' : '' ?>">
                Пользователи
            </a>
        </li>

        <li class="nav-item">
            <a href="orders.php"
               class="nav-link <?= ($currentPage == 'orders.php') ? 'active' : '' ?>">
                Заказы
            </a>
        </li>

    </ul>

</div>