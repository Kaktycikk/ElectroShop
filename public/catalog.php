<?php

include __DIR__ . '/../includes/db.php';

$title = 'Каталог товаров';

if (isset($_GET['category']))
{
    $category = (int)$_GET['category'];

    switch ($category)
    {
        case 1:
            $title = 'Смартфоны';
            break;

        case 2:
            $title = 'Ноутбуки';
            break;

        case 3:
            $title = 'Видеокарты';
            break;

        case 4:
            $title = 'Мониторы';
            break;
    }

    $result = pg_query(
        $conn,
        "SELECT * FROM products WHERE category_id = $category"
    );
}
else
{
    $result = pg_query(
        $conn,
        "SELECT * FROM products"
    );
}

include __DIR__ . '/../includes/site_header.php';

?>

<!-- Баннер каталога -->

<section class="catalog-banner">

    <div class="container text-center">

        <h1 class="catalog-title">

            Каталог товаров

        </h1>

        <p class="catalog-text">

            Более 10 000 товаров для работы,
            игр и повседневной жизни

        </p>

    </div>

</section>

<!-- Категории -->

<section class="py-5">

    <div class="container">

        <div class="row g-3">

            <div class="col-md-3">

                <a href="catalog.php?category=1"
                   class="text-decoration-none text-dark">

                    <div class="category-box">

                        <i class="bi bi-phone"></i>

                        <span>Смартфоны</span>

                    </div>

                </a>

            </div>

            <div class="col-md-3">

                <a href="catalog.php?category=2"
                   class="text-decoration-none text-dark">

                    <div class="category-box">

                        <i class="bi bi-laptop"></i>

                        <span>Ноутбуки</span>

                    </div>

                </a>

            </div>

            <div class="col-md-3">

                <a href="catalog.php?category=3"
                   class="text-decoration-none text-dark">

                    <div class="category-box">

                        <i class="bi bi-gpu-card"></i>

                        <span>Видеокарты</span>

                    </div>

                </a>

            </div>

            <div class="col-md-3">

                <a href="catalog.php?category=4"
                   class="text-decoration-none text-dark">

                    <div class="category-box">

                        <i class="bi bi-display"></i>

                        <span>Мониторы</span>

                    </div>

                </a>

            </div>

        </div>

    </div>

</section>

<!-- Товары -->

<section class="pb-5">

    <div class="container">

        <h2 class="mb-4">

            <?= $title ?>

        </h2>

        <div class="row g-4">

            <?php while ($product = pg_fetch_assoc($result)): ?>

                <div class="col-lg-3 col-md-6">

                    <div class="card product-card h-100">

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

                            <a href="product.php?id=<?= $product['id'] ?>"
                            class="btn btn-warning w-100">

                                Подробнее

                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>