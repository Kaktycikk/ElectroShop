<?php

session_start();

include __DIR__ . '/../includes/db.php';

$categories = pg_query(
    $conn,
    "
    SELECT
        id,
        name
    FROM categories
    ORDER BY name
    "
);

if (!$categories)
{
    die(pg_last_error($conn));
}

$category = (int)($_GET["category"] ?? 0);
$search = trim($_GET["search"] ?? "");

$title = "Каталог товаров";

if ($category > 0)
{
    $categoryName = pg_fetch_result(
        pg_query_params(
            $conn,
            "
            SELECT name
            FROM categories
            WHERE id = $1
            ",
            [
                $category
            ]
        ),
        0,
        0
    );

    if ($categoryName)
    {
        $title = $categoryName;
    }
}

$sql = "
SELECT
    products.*,
    categories.name AS category_name
FROM products
LEFT JOIN categories
    ON categories.id = products.category_id
WHERE 1=1
";

$params = [];
$index = 1;

if ($category > 0)
{
    $sql .= "
        AND category_id = $" . $index;

    $params[] = $category;

    $index++;
}

if ($search !== "")
{
    $sql .= "
        AND (
            LOWER(products.name) LIKE LOWER($" . $index . ")
            OR LOWER(products.brand) LIKE LOWER($" . $index . ")
        )
    ";

    $params[] = "%" . $search . "%";

    $index++;
}

$result = pg_query_params(
    $conn,
    $sql,
    $params
);

if (!$result)
{
    die(pg_last_error($conn));
}

include __DIR__ . '/../includes/site_header.php';

?>

<!-- Панель фильтрации -->

<section class="pb-4">

    <div class="container">

        <div class="filter-box">

            <div class="mb-4">

                <h5 class="mb-1 fw-bold">

                    <i class="bi bi-sliders me-2 text-warning"></i>

                    Фильтры

                </h5>

                <p class="text-muted mb-0">

                    Найдите нужный товар по названию, бренду или категории

                </p>

            </div>

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">

                            Поиск

                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Название товара или бренд..."
                            value="<?= htmlspecialchars($search) ?>">

                    </div>

                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">

                            Категория

                        </label>

                        <select
                            name="category"
                            class="form-select">

                            <option value="">

                                Все категории

                            </option>

                            <?php while ($category = pg_fetch_assoc($categories)): ?>

                                <option
                                    value="<?= $category["id"] ?>"
                                    <?= (($_GET["category"] ?? "") == $category["id"])
                                        ? "selected"
                                        : "" ?>>

                                    <?= htmlspecialchars($category["name"]) ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <div class="col-lg-3">

                        <button
                            class="btn btn-warning w-100">

                            <i class="bi bi-search me-2"></i>

                            Найти товары

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</section>

<!-- Товары -->

<section class="pb-5">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">

                    <?= htmlspecialchars($title) ?>

                </h3>

                <p class="text-muted mb-0">

                    Найдено
                    <?= pg_num_rows($result) ?>
                    товаров

                </p>

            </div>

        </div>

        <div class="row g-4">

            <?php if (pg_num_rows($result) == 0): ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center p-5">

                        <i class="bi bi-search fs-1 d-block mb-3"></i>

                        <h4>

                            Ничего не найдено

                        </h4>

                        <p class="mb-0">

                            Попробуйте изменить параметры поиска.

                        </p>

                    </div>

                </div>

            <?php else: ?>

                <?php while ($product = pg_fetch_assoc($result)): ?>

                    <div class="col-lg-3 col-md-6">

                        <div class="card product-card h-100">

                            <img src="img/products/<?= $product['image'] ?>"
                                class="card-img-top"
                                alt="<?= $product['name'] ?>">

                            <div class="card-body">

                            <span class="badge bg-warning text-dark mb-2">

                                <?= htmlspecialchars($product["category_name"]) ?>

                            </span>

                                <h5 class="fw-bold mb-2">

                                    <?= $product['name'] ?>

                                </h5>

                                <p class="text-secondary small mb-3">

                                    <?= $product['brand'] ?>

                                </p>

                                <h4 class="fw-bold text-dark mb-3">

                                    <?= number_format($product['price'], 0, ',', ' ') ?> ₽

                                </h4>

                                <a href="product.php?id=<?= $product['id'] ?>"
                                    class="btn btn-warning w-100">
                                    <i class="bi bi-arrow-right me-2"></i>

                                    Подробнее

                                </a>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>
            <?php endif; ?>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>