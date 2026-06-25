<?php

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>ElectroShop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
          href="css/site.css">

</head>

<body>

<header class="site-header <?= ($currentPage != 'index.php') ? 'inner-header' : '' ?>">

    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-dark">

            <a class="navbar-brand"
               href="index.php">

                ElectroShop

            </a>

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Главная
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="catalog.php">
                        Каталог
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="sales.php">
                        Акции
                    </a>
                </li>
            </ul>

            <div class="header-icons">

                <?php if (isset($_SESSION["user_id"])): ?>

                    <span class="user-name">

                        <i class="bi bi-person-circle"></i>

                        <?= htmlspecialchars($_SESSION["user_name"]) ?>

                    </span>

                    <a
                        href="orders.php"
                        title="Мои заказы">

                        <i class="bi bi-bag-check"></i>

                    </a>

                    <a
                        href="logout.php"
                        title="Выйти">

                        <i class="bi bi-box-arrow-right"></i>

                    </a>

                <?php else: ?>

                    <a href="login.php"
                    title="Войти">

                        <i class="bi bi-person"></i>

                    </a>

                <?php endif; ?>

                <a href="cart.php"
                title="Корзина">

                    <i class="bi bi-cart"></i>

                </a>

            </div>

        </nav>

    </div>

</header>
<main>