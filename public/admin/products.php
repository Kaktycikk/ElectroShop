<?php

include __DIR__ . '/../../includes/admin_header.php';
include __DIR__ . '/../../includes/db.php';

$categories = pg_query(
    $conn,
    "SELECT id, name
     FROM categories
     ORDER BY name"
);

$categoryList = [];

while ($cat = pg_fetch_assoc($categories))
{
    $categoryList[] = $cat;
}

if (isset($_POST["add_product"]))
{
    $name = trim($_POST["name"]);
    $brand = trim($_POST["brand"]);
    $category = (int)$_POST["category_id"];
    $description = trim($_POST["description"]);
    $price = (float)$_POST["price"];
    $stock = (int)$_POST["stock"];

    $imageName = null;

    if (!empty($_FILES["image"]["name"]))
    {
        $extension = pathinfo(
            $_FILES["image"]["name"],
            PATHINFO_EXTENSION
        );

        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array(strtolower($extension), $allowed))
        {
            die("Можно загружать только JPG, PNG или WEBP.");
        }

        $imageName =
            time() .
            "_" .
            basename($_FILES["image"]["name"]);

        if (!move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            __DIR__ . "/../img/products/" . $imageName
        ))
        {
            die("Не удалось загрузить изображение.");
        }
    }

    $result = pg_query_params(
        $conn,
        "
        INSERT INTO products
        (
            name,
            brand,
            description,
            image,
            category_id,
            price,
            stock
        )
        VALUES
        (
            $1,$2,$3,$4,$5,$6,$7
        )
        ",
        [
            $name,
            $brand,
            $description,
            $imageName,
            $category,
            $price,
            $stock
        ]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: products.php");
    exit;
}

if (isset($_POST["edit_product"]))
{
    $productId = (int)$_POST["product_id"];

    $name = trim($_POST["name"]);
    $brand = trim($_POST["brand"]);
    $category = (int)$_POST["category_id"];
    $description = trim($_POST["description"]);
    $price = (float)$_POST["price"];
    $stock = (int)$_POST["stock"];

    // Получаем текущее изображение товара
    $oldProduct = pg_fetch_assoc(
        pg_query_params(
            $conn,
            "SELECT image
             FROM products
             WHERE id = $1",
            [$productId]
        )
    );

    $imageName = $oldProduct["image"];

    // Если выбрали новую картинку
    if (!empty($_FILES["image"]["name"]))
    {
        $extension = strtolower(
            pathinfo(
                $_FILES["image"]["name"],
                PATHINFO_EXTENSION
            )
        );

        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($extension, $allowed))
        {
            die("Можно загружать только JPG, PNG или WEBP.");
        }

        $imageName =
            time() .
            "_" .
            basename($_FILES["image"]["name"]);

        if (!move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            __DIR__ . "/../img/products/" . $imageName
        ))
        {
            die("Не удалось загрузить изображение.");
        }
    }

    $result = pg_query_params(
        $conn,
        "
        UPDATE products
        SET
            name = $1,
            brand = $2,
            description = $3,
            image = $4,
            category_id = $5,
            price = $6,
            stock = $7
        WHERE id = $8
        ",
        [
            $name,
            $brand,
            $description,
            $imageName,
            $category,
            $price,
            $stock,
            $productId
        ]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: products.php");
    exit;
}

if (isset($_POST["delete_product"]))
{
    $productId = (int)$_POST["product_id"];

    $result = pg_query_params(
        $conn,
        "
        DELETE FROM products
        WHERE id = $1
        ",
        [$productId]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: products.php");
    exit;
}

$search = $_GET["search"] ?? "";
$category = $_GET["category"] ?? "all";

$sql = "
    SELECT
        products.id,
        products.name,
        products.brand,
        products.description,
        products.image,
        categories.id AS category_id,
        categories.name AS category,
        products.price,
        products.stock
    FROM products
    JOIN categories
        ON categories.id = products.category_id
    WHERE 1=1
    ";

$params = [];
$index = 1;

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

if ($category !== "all")
{
    $sql .= " AND categories.id = $" . $index;
    $params[] = $category;
    $index++;
}

$sql .= " ORDER BY products.id";

$products = pg_query_params($conn, $sql, $params);

if (!$products)
{
    die(pg_last_error($conn));
}

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">
                <h1>Товары</h1>
                <p>Управление каталогом интернет-магазина</p>
            </div>

            <div class="d-flex align-items-center mb-4 gap-3">

                <h3 class="mb-0 me-3">
                    Список товаров
                </h3>

                <form
                    method="GET"
                    class="d-flex align-items-center gap-3 flex-grow-1">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        style="max-width:450px;"
                        placeholder="Поиск товара..."
                        value="<?= htmlspecialchars($search) ?>">

                    <select
                        class="form-select"
                        name="category">

                        <option
                            value="all"
                            <?= $category === "all" ? "selected" : "" ?>>
                            Все категории
                        </option>

                        <?php foreach ($categoryList as $cat): ?>

                            <option
                                value="<?= $cat["id"] ?>"
                                <?= ($category == $cat["id"]) ? "selected" : "" ?>>

                                <?= htmlspecialchars($cat["name"]) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <button
                        type="submit"
                        class="edit-btn">

                        <i class="bi bi-search"></i>

                    </button>

                </form>

                <button
                    class="add-btn"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#addProductModal">

                    <i class="bi bi-plus-lg me-2"></i>

                    Добавить товар

                </button>

            </div>
           

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Фото</th>
                            <th>Название</th>
                            <th>Бренд</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Остаток</th>
                            <th>Действия</th>
                        </tr>
                    </thead>

                    <tbody>

<?php while ($product = pg_fetch_assoc($products)): ?>

<tr>

    <td>
        <?php if ($product["image"]): ?>

            <img
            src="/img/products/<?= htmlspecialchars($product["image"]) ?>"
            alt=""
            style="
                width:72px;
                height:72px;
                object-fit:contain;
                border-radius:10px;
                background:#fff;
                padding:4px;
            ">

        <?php endif; ?>
    </td>

    <td><?= htmlspecialchars($product["name"]) ?></td>

    <td><?= htmlspecialchars($product["brand"]) ?></td>

    <td><?= htmlspecialchars($product["category"]) ?></td>

    <td><?= number_format($product["price"], 0, ",", " ") ?> ₽</td>

    <td><?= $product['stock']; ?></td>

    <td>

        <div class="table-actions">

            <button
                class="edit-btn edit-product-btn"

                data-id="<?= $product["id"] ?>"
                data-name="<?= htmlspecialchars($product["name"]) ?>"
                data-brand="<?= htmlspecialchars($product["brand"]) ?>"
                data-category="<?= $product["category_id"] ?>"
                data-description="<?= htmlspecialchars($product["description"]) ?>"
                data-price="<?= $product["price"] ?>"
                data-stock="<?= $product["stock"] ?>"
                data-image="<?= htmlspecialchars($product["image"]) ?>"

                data-bs-toggle="modal"
                data-bs-target="#editProductModal">

                <i class="bi bi-pencil-fill"></i>

            </button>

            <button
                class="delete-btn delete-product-btn"

                data-id="<?= $product["id"] ?>"
                data-name="<?= htmlspecialchars($product["name"]) ?>"

                data-bs-toggle="modal"
                data-bs-target="#deleteProductModal">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

    </td>

</tr>

<?php endwhile; ?>

</tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- Добавление товара -->

<div class="modal fade" id="addProductModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Добавить товар
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form 
                method="POST"
                enctype="multipart/form-data">

                    <div class="mb-3">

                        <label class="form-label">
                            Название товара
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Бренд
                        </label>

                        <input
                            type="text"
                            name="brand"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Категория
                        </label>

                        <select class="form-select"
                            name="category_id">

                            <?php foreach ($categoryList as $cat): ?>

                                <option value="<?= $cat["id"] ?>">

                                    <?= htmlspecialchars($cat["name"]) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Изображение
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            name="image"
                            accept="image/*">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Описание
                        </label>

                        <textarea
                            class="form-control"
                            rows="4"
                            name="description">
                        </textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Цена
                        </label>

                        <input
                            type="number"
                            name="price"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Остаток
                        </label>

                        <input
                            name="stock"
                            type="number"
                            class="form-control">

                    </div>
                
                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-modal-cancel"
                            data-bs-dismiss="modal">

                            <i class="bi bi-x-lg me-2"></i>
                            Отмена

                        </button>

                        <button
                            type="submit"
                            name="add_product"
                            class="btn btn-modal-save">

                            <i class="bi bi-check-lg me-2"></i>
                            Сохранить

                        </button>

                    </div>


                </form>

            </div>

            
        </div>

    </div>

</div>

<!-- Редактирование товара -->

<div class="modal fade" id="editProductModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Редактировать товар
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form
                    method="POST"
                    enctype="multipart/form-data">

                    <input
                        type="hidden"
                        name="product_id"
                        id="editProductId">

                    <div class="mb-3">

                        <label class="form-label">
                            Название товара
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="editName"
                            name="name">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Бренд
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="editBrand"
                            name="brand">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Категория
                        </label>

                        <select
                            class="form-select"
                            id="editCategory"
                            name="category_id">

                            <?php foreach ($categoryList as $cat): ?>

                                <option value="<?= $cat["id"] ?>">

                                    <?= htmlspecialchars($cat["name"]) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Новое изображение

                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="editImage"
                            name="image"
                            accept="image/*">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Текущее изображение
                        </label>

                        <div class="text-center">

                            <img
                                id="editPreview"
                                src=""
                                alt="Изображение товара"
                                style="
                                    width:120px;
                                    height:120px;
                                    object-fit:contain;
                                    border:1px solid #dee2e6;
                                    border-radius:12px;
                                    background:#fff;
                                    padding:6px;
                                ">

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Описание
                        </label>

                        <textarea
                            class="form-control"
                            rows="4"
                            id="editDescription"
                            name="description">
                        </textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Цена
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="editPrice"
                            name="price">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Остаток
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="editStock"
                            name="stock">

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-modal-cancel"
                            data-bs-dismiss="modal">

                            <i class="bi bi-x-lg me-2"></i>
                            Отмена

                        </button>

                        <button
                            type="submit"
                            name="edit_product"
                            class="btn btn-modal-save">

                            <i class="bi bi-check-lg me-2"></i>
                            Сохранить

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    
</div>

<!-- Удаление товара -->

<div class="modal fade" id="deleteProductModal">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST">

                <input
                    type="hidden"
                    id="deleteProductId"
                    name="product_id">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Удаление товара
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body text-center">

                    <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-3"></i>

                    <p class="mb-2">

                        Вы действительно хотите удалить товар

                    </p>

                    <h5 class="fw-bold text-dark mb-3"
                        id="deleteProductName">
                    </h5>

                    <p class="delete-warning mb-0">
                        Это действие нельзя отменить.
                    </p>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-modal-cancel"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-lg me-2"></i>
                        Отмена

                    </button>

                    <button
                        type="submit"
                        name="delete_product"
                        class="btn btn-modal-delete">

                        <i class="bi bi-trash3 me-2"></i>
                        Удалить

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>