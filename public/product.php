<?php

include __DIR__ . '/../includes/db.php';

$id = (int)$_GET["id"];

$result = pg_query_params(
    $conn,
    "SELECT * FROM products WHERE id = $1",
    array($id)
);

$product = pg_fetch_assoc($result);

if (!$product)
{
    header("Location: catalog.php");
    exit;
}

include __DIR__ . '/../includes/site_header.php';

?>

<section class="product-section py-5">

    <div class="container">

        <div class="row g-5">

            <!-- Фото товара -->

            <div class="col-lg-6">

                <div class="product-image">

                    <img src="img/products/<?= $product['image'] ?>"
                         class="img-fluid"
                         alt="<?= $product['name'] ?>">

                </div>

            </div>

            <!-- Информация -->

            <div class="col-lg-6">

                <?php if ($product["stock"] > 0): ?>

                    <span class="badge bg-success mb-3">

                        <i class="bi bi-check-circle-fill me-1"></i>

                        В наличии

                    </span>

                <?php else: ?>

                    <span class="badge bg-danger mb-3">

                        <i class="bi bi-x-circle-fill me-1"></i>

                        Отсутствует на складе

                    </span>

                <?php endif; ?>

                <h1 class="product-title">

                    <?= $product['name'] ?>

                </h1>

                <p class="text-muted">

                    <?= $product['brand'] ?>

                </p>

                <h2 class="product-price fw-bold mb-4">

                    <?= number_format($product['price'], 0, ',', ' ') ?> ₽

                </h2>              
            
               <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">

                                <i class="bi bi-box-seam me-2"></i>

                                Наличие

                            </span>

                            <?php if ($product["stock"] > 0): ?>

                                <strong class="text-success">

                                    <?= $product["stock"] ?> шт.

                                </strong>

                            <?php else: ?>

                                <strong class="text-danger">

                                    Нет в наличии

                                </strong>

                            <?php endif; ?>

                        </div>

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">

                                <i class="bi bi-truck me-2"></i>

                                Доставка

                            </span>

                            <strong>

                                1–3 дня

                            </strong>

                        </div>

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">

                                <i class="bi bi-shield-check me-2"></i>

                                Гарантия

                            </span>

                            <strong>

                                12 месяцев

                            </strong>

                        </div>

                    </div>

                </div>

                <div class="d-flex gap-3 mt-4">

                    <?php if ($product["stock"] > 0): ?>

                        <form action="add_to_cart.php" method="POST">

                            <input
                                type="hidden"
                                name="product_id"
                                value="<?= $product["id"] ?>">

                            <button
                                type="submit"
                                class="btn btn-warning btn-lg w-100">

                                <i class="bi bi-cart-plus me-2"></i>

                                Добавить в корзину

                            </button>

                        </form>

                    <?php else: ?>

                        <button
                            class="btn btn-secondary btn-lg"
                            disabled>

                            <i class="bi bi-slash-circle me-2"></i>

                            Нет в наличии

                        </button>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <!-- Дополнительная информация -->

        <div class="card shadow mt-5">

            <div class="card-body">

                <h3 class="mb-3">

                    <i class="bi bi-file-text me-2 text-warning"></i>   

                    Описание товара

                </h3>

                <p>

                    <?= $product['description'] ?>

                </p>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>