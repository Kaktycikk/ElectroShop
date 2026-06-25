<?php 
include __DIR__ . '/../includes/db.php';

$bestsellers = pg_query(
    $conn,
    "SELECT * FROM products LIMIT 3"
);
?>

<?php include __DIR__ . '/../includes/site_header.php'; ?>

<!-- Главный экран -->

<section class="hero-section">

    <div class="hero-overlay">

        <div class="container">

            <div class="row align-items-center min-vh-100">

                <div class="col-lg-6">

                    <span class="hero-label">

                        ЛУЧШИЕ ПРЕДЛОЖЕНИЯ 2026

                    </span>

                    <h1 class="hero-title">

                        Электроника
                        для работы,
                        игр и жизни

                    </h1>

                    <p class="hero-text">

                        Смартфоны, ноутбуки,
                        видеокарты и комплектующие
                        по выгодным ценам.

                    </p>

                    <div class="mt-4">

                        <a href="catalog.php"
                        class="btn btn-warning btn-lg me-3">

                            Каталог

                        </a>

                        <a href="sales.php"
                        class="btn btn-outline-light btn-lg">

                            Акции

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- Преимущества -->

<section class="py-5">

    <div class="container">

        <h2 class="text-center mb-5">

            Почему выбирают нас

        </h2>

        <div class="row text-center">

            <div class="col-md-4">

                <i class="bi bi-truck display-4"></i>

                <h4 class="mt-3">

                    Быстрая доставка

                </h4>

                <p>

                    Доставка по всей стране.

                </p>

            </div>

            <div class="col-md-4">

                <i class="bi bi-shield-check display-4"></i>

                <h4 class="mt-3">

                    Гарантия качества

                </h4>

                <p>

                    Только оригинальная техника.

                </p>

            </div>

            <div class="col-md-4">

                <i class="bi bi-headset display-4"></i>

                <h4 class="mt-3">

                    Поддержка 24/7

                </h4>

                <p>

                    Поможем с любым вопросом.

                </p>

            </div>

        </div>

    </div>

</section>

<!-- Популярные категории -->

<section class="py-5">

    <div class="container">

        <h2 class="text-center mb-5">

            Популярные категории

        </h2>

        <div class="row g-4">

            <div class="col-md-3">

                <a href="catalog.php?category=1"
                    class="text-decoration-none text-dark">

                    <div class="card shadow text-center h-100 category-card">

                        <div class="card-body">

                            <i class="bi bi-phone display-4"></i>

                            <h5 class="mt-3">

                                Смартфоны

                            </h5>

                        </div>

                    </div>
                </a>

            </div>

            <div class="col-md-3">

                <a href="catalog.php?category=2"
                    class="text-decoration-none text-dark">

                    <div class="card shadow text-center h-100 category-card">

                        <div class="card-body">

                            <i class="bi bi-laptop display-4"></i>

                            <h5 class="mt-3">

                                Ноутбуки

                            </h5>

                        </div>

                    </div>
                </a>

            </div>

            <div class="col-md-3">

                <a href="catalog.php?category=3"
                    class="text-decoration-none text-dark">

                    <div class="card shadow text-center h-100 category-card">

                        <div class="card-body">

                            <i class="bi bi-gpu-card display-4"></i>

                            <h5 class="mt-3">

                                Видеокарты

                            </h5>

                        </div>

                    </div>
                </a>

            </div>

            <div class="col-md-3">

                <a href="catalog.php?category=4"
                    class="text-decoration-none text-dark">

                    <div class="card shadow text-center h-100 category-card">

                        <div class="card-body">

                            <i class="bi bi-display display-4"></i>

                            <h5 class="mt-3">

                                Мониторы

                            </h5>

                        </div>

                    </div>
                </a>

            </div>

        </div>

    </div>

</section>

<!-- Хиты продаж -->

<section class="bestsellers-section bg-light py-5">

    <div class="container">

        <h2 class="text-center mb-5">

            Хиты продаж

        </h2>

        <div class="row g-4">

            <?php while ($product = pg_fetch_assoc($bestsellers)): ?>

                <div class="col-md-4">

                    <a href="product.php?id=<?= $product['id'] ?>"
                        class="text-decoration-none text-dark">

                        <div class="card shadow h-100 product-card">

                            <img src="img/products/<?= $product['image'] ?>"
                                class="card-img-top"
                                alt="<?= $product['name'] ?>">

                            <div class="card-body">

                                <h5>

                                    <?= $product['name'] ?>

                                </h5>

                                <p class="text-muted">

                                    <?= $product['brand'] ?>

                                </p>

                                <h4>

                                    <?= number_format($product['price'], 0, ',', ' ') ?> ₽

                                </h4>

                            </div>

                        </div>

                    </a>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

</section>

<!-- Бренды -->

<section class="py-5 ">

    <div class="container">

        <h2 class="text-center mb-5">

            Популярные бренды

        </h2>

        <div class="row g-4">

            <div class="col-md-2">
                <div class="brand-card">
                    <img src="img/brands/APPLE.svg"
                    alt="Apple">
                </div>
            </div>

            <div class="col-md-2">
                <div class="brand-card">
                    <img src="img/brands/SAMSUNG.svg"
                    alt="Samsung">
                </div>
            </div>

            <div class="col-md-2">
                <div class="brand-card">
                    <img src="img/brands/ASUS.svg"
                    alt="Asus">
                </div>
            </div>

            <div class="col-md-2">
                <div class="brand-card"><img src="img/brands/HP.svg"
                    alt="HP"></div>
            </div>

            <div class="col-md-2">
                <div class="brand-card"><img src="img/brands/XIAOMI.svg"
                    alt="Xiaomi"></div>
            </div>

            <div class="col-md-2">
                <div class="brand-card"><img src="img/brands/INTEL.svg"
                    alt="Intel"></div>
            </div>

        </div>

    </div>

</section>

<!-- Статистика -->

<section class="stats-section py-5 text-white">

    <div class="container">

        <div class="row text-center">

            <div class="col-md-3">

                <h2>10 000+</h2>

                <p>Товаров</p>

            </div>

            <div class="col-md-3">

                <h2>5 000+</h2>

                <p>Клиентов</p>

            </div>

            <div class="col-md-3">

                <h2>12</h2>

                <p>Лет на рынке</p>

            </div>

            <div class="col-md-3">

                <h2>24/7</h2>

                <p>Поддержка</p>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>