<?php

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

include __DIR__ . '/../../includes/db.php';

if (isset($_POST["add_category"]))
{
    $name = trim($_POST["name"]);

    // Проверяем, существует ли категория
    $exists = pg_fetch_result(
        pg_query_params(
            $conn,
            "SELECT COUNT(*) FROM categories WHERE LOWER(name) = LOWER($1)",
            [$name]
        ),
        0,
        0
    );

    if ($exists > 0)
    {
        $_SESSION["category_error"] =
            "Категория с таким названием уже существует.";

        header("Location: categories.php");
        exit;
    }
    // Добавляем категорию
    $result = pg_query_params(
        $conn,
        "
        INSERT INTO categories (name)
        VALUES ($1)
        ",
        [
            $name
        ]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: categories.php");
    exit;
}

if (isset($_POST["delete_category"]))
{
    $categoryId = (int)$_POST["category_id"];

    // Сколько сейчас категорий
    $count = (int) pg_fetch_result(
        pg_query(
            $conn,
            "SELECT COUNT(*) FROM categories"
        ),
        0,
        0
    );

    // Если категория последняя — запрещаем удаление
    if ($count === 1)
    {
        $_SESSION["category_delete_error"] =
            "Нельзя удалить последнюю категорию.";

        header("Location: categories.php");
        exit;
    }

    // Удаляем товары категории
    pg_query_params(
        $conn,
        "
        DELETE FROM products
        WHERE category_id = $1
        ",
        [
            $categoryId
        ]
    );

    // Удаляем категорию
    $result = pg_query_params(
        $conn,
        "
        DELETE FROM categories
        WHERE id = $1
        ",
        [
            $categoryId
        ]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: categories.php");
    exit;
}

$search = trim($_GET["search"] ?? "");
$sql = "
SELECT
    categories.id,
    categories.name,
    COUNT(products.id) AS products_count,
    COALESCE(SUM(products.stock), 0) AS stock_count
FROM categories
LEFT JOIN products
    ON products.category_id = categories.id
WHERE 1=1
";

$params = [];
$index = 1;

if ($search !== "")
{
    $sql .= "
        AND LOWER(categories.name)
        LIKE LOWER($" . $index . ")
    ";

    $params[] = "%" . $search . "%";
    $index++;
}

$sql .= "
GROUP BY
    categories.id,
    categories.name
ORDER BY
    categories.id
";

$categories = pg_query_params(
    $conn,
    $sql,
    $params
);

if (!$categories)
{
    die(pg_last_error($conn));
}

include __DIR__ . '/../../includes/admin_header.php';

$error = $_SESSION["category_error"] ?? "";

unset($_SESSION["category_error"]);

$deleteError = $_SESSION["category_delete_error"] ?? "";

unset($_SESSION["category_delete_error"]);

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">

                <h1>Категории</h1>

                <p>
                    Управление категориями товаров
                </p>

            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <form
                    method="GET"
                    class="d-flex"
                    style="max-width:350px; width:100%;">

                    <input
                        type="text"
                        class="form-control me-2"
                        name="search"
                        placeholder="Поиск категории..."

                        value="<?= htmlspecialchars($search) ?>">

                    <button
                        type="submit"
                        class="edit-btn">

                        <i class="bi bi-search"></i>

                    </button>

                </form>

                <button
                    class="add-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#addCategoryModal">

                    <i class="bi bi-plus-lg me-2"></i>

                    Добавить категорию

                </button>

            </div>

            <div class="table-wrapper">

                <table class="table">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Название категории</th>
                            <th>Товаров</th>
                            <th>На складе</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($category = pg_fetch_assoc($categories)): ?>

                        <tr>

                            <td><?= $category['id']; ?></td>

                            <td><?= $category['name']; ?></td>

                            <td><?= $category["products_count"] ?></td>

                            <td><?= $category["stock_count"] ?></td>

                            <td>

                                <button
                                    class="delete-btn delete-category-btn"

                                    data-id="<?= $category["id"] ?>"
                                    data-name="<?= htmlspecialchars($category["name"]) ?>"
                                    data-count="<?= $category["products_count"] ?>"

                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal">

                                    <i class="bi bi-x-lg"></i>

                                </button>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- Добавление категории -->

<div class="modal fade" id="addCategoryModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Добавить категорию
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST">

                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-circle me-2"></i>

                            <?= htmlspecialchars($error) ?>

                        </div>

                        <?php endif; ?>

                    <div class="mb-3">

                        <label class="form-label">
                            Название категории
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="name"
                            required>

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
                            name="add_category"
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

<!-- Удаление категории -->

<div class="modal fade" id="deleteCategoryModal">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST">

                <input
                    type="hidden"
                    id="deleteCategoryId"
                    name="category_id">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Удаление категории

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <?php if (!empty($deleteError)): ?>

                    <div class="alert alert-danger">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        <?= htmlspecialchars($deleteError) ?>

                    </div>

                    <?php endif; ?>

                    Вы действительно хотите удалить категорию
                    <strong id="deleteCategoryName"></strong>?

                    <div
                        id="deleteCategoryWarning"
                        class="alert alert-warning mt-3 mb-0">

                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

                        Будут также удалены
                        <strong id="deleteCategoryCount"></strong>
                        товаров этой категории.

                    </div>

                    <div
                        id="deleteCategoryEmpty"
                        class="alert alert-info mt-3 mb-0 d-none">

                        <i class="bi bi-info-circle-fill me-2"></i>

                        В этой категории нет товаров.

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-modal-cancel"
                        data-bs-dismiss="modal">

                        Отмена

                    </button>

                    <button
                        type="submit"
                        name="delete_category"
                        class="btn btn-danger">

                        Удалить

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>

<?php if (!empty($error)): ?>

<script>

document.addEventListener("DOMContentLoaded", () => {

    new bootstrap.Modal(
        document.getElementById("addCategoryModal")
    ).show();

});

</script>

<?php endif; ?>

<?php if (!empty($deleteError)): ?>

<script>

document.addEventListener("DOMContentLoaded", () => {

    new bootstrap.Modal(
        document.getElementById("deleteCategoryModal")
    ).show();

});

</script>

<?php endif; ?>