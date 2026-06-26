<?php

$products = [
    [
        'id' => 1,
        'name' => 'iPhone 16 Pro',
        'category' => 'Смартфоны',
        'price' => '89 990 ₽',
        'stock' => 14
    ],
    [
        'id' => 2,
        'name' => 'RTX 5070',
        'category' => 'Видеокарты',
        'price' => '75 990 ₽',
        'stock' => 8
    ],
    [
        'id' => 3,
        'name' => 'MacBook Air M5',
        'category' => 'Ноутбуки',
        'price' => '119 990 ₽',
        'stock' => 5
    ]
];

include __DIR__ . '/../../includes/admin_header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">
                <h1>Товары</h1>
                <p>Управление каталогом интернет-магазина</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3>Список товаров</h3>

                <button
                    class="btn btn-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#addProductModal">

                    Добавить товар

                </button>

            </div>

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Остаток</th>
                            <th>Действия</th>
                        </tr>
                    </thead>

                    <tbody>

<?php foreach ($products as $product): ?>

<tr>

    <td><?= $product['id']; ?></td>

    <td><?= $product['name']; ?></td>

    <td><?= $product['category']; ?></td>

    <td><?= $product['price']; ?></td>

    <td><?= $product['stock']; ?></td>

    <td>

        <button
            class="btn btn-sm btn-warning"
            data-bs-toggle="modal"
            data-bs-target="#editProductModal">

            <i class="bi bi-pencil"></i>

        </button>

        <button
            class="btn btn-sm btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#deleteProductModal">

            <i class="bi bi-trash"></i>

        </button>

    </td>

</tr>

<?php endforeach; ?>

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

                <form>

                    <div class="mb-3">

                        <label class="form-label">
                            Название товара
                        </label>

                        <input
                            type="text"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Категория
                        </label>

                        <select class="form-select">

                            <option>Смартфоны</option>
                            <option>Ноутбуки</option>
                            <option>Мониторы</option>
                            <option>Видеокарты</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Цена
                        </label>

                        <input
                            type="number"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Остаток
                        </label>

                        <input
                            type="number"
                            class="form-control">

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Отмена

                </button>

                <button class="btn btn-warning">

                    Сохранить

                </button>

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

                <form>

                    <div class="mb-3">

                        <label class="form-label">
                            Название товара
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="iPhone 16 Pro">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Категория
                        </label>

                        <select class="form-select">

                            <option selected>
                                Смартфоны
                            </option>

                            <option>
                                Ноутбуки
                            </option>

                            <option>
                                Мониторы
                            </option>

                            <option>
                                Видеокарты
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Цена
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            value="89990">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Остаток
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            value="14">

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Отмена

                </button>

                <button class="btn btn-warning">

                    Сохранить

                </button>

            </div>

        </div>

    </div>
    
</div>
<!-- Удаление товара -->

<div class="modal fade" id="deleteProductModal">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

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

            <div class="modal-body">

                Вы действительно хотите удалить товар?

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Отмена

                </button>

                <button
                    class="btn btn-danger">

                    Удалить

                </button>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>