<?php

$categories = [
    [
        'id' => 1,
        'name' => 'Смартфоны',
        'count' => 24
    ],
    [
        'id' => 2,
        'name' => 'Ноутбуки',
        'count' => 18
    ],
    [
        'id' => 3,
        'name' => 'Мониторы',
        'count' => 11
    ],
    [
        'id' => 4,
        'name' => 'Видеокарты',
        'count' => 9
    ]
];

include __DIR__ . '/../includes/admin_header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">

                <h1>Категории</h1>

                <p>
                    Управление категориями товаров
                </p>

            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3>Список категорий</h3>

                <button
                    class="btn btn-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#addCategoryModal">

                    Добавить категорию

                </button>

            </div>

            <div class="table-wrapper">

                <table class="table">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Название категории</th>
                            <th>Количество товаров</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($categories as $category): ?>

                        <tr>

                            <td><?= $category['id']; ?></td>

                            <td><?= $category['name']; ?></td>

                            <td><?= $category['count']; ?></td>

                            <td>

                                <button
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal">

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

                <form>

                    <div class="mb-3">

                        <label class="form-label">
                            Название категории
                        </label>

                        <input
                            type="text"
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

<!-- Удаление категории -->

<div class="modal fade" id="deleteCategoryModal">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content">

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

                Вы действительно хотите удалить категорию?

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

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>