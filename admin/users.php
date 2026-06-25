<?php

$users = [
    [
        'id' => 1,
        'name' => 'Иван Петров',
        'email' => 'ivan@mail.ru',
        'role' => 'Администратор'
    ],
    [
        'id' => 2,
        'name' => 'Анна Смирнова',
        'email' => 'anna@mail.ru',
        'role' => 'Менеджер'
    ],
    [
        'id' => 3,
        'name' => 'Дмитрий Иванов',
        'email' => 'dima@mail.ru',
        'role' => 'Покупатель'
    ]
];

include __DIR__ . '/../includes/admin_header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">

                <h1>Пользователи</h1>

                <p>
                    Управление пользователями системы
                </p>

            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3>Список пользователей</h3>

            </div>

            <div class="table-wrapper">

                <table class="table">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($users as $user): ?>

                        <tr>

                            <td><?= $user['id']; ?></td>

                            <td><?= $user['name']; ?></td>

                            <td><?= $user['email']; ?></td>

                            <td><?= $user['role']; ?></td>

                            <td>

                                <button
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal">

                                    <i class="bi bi-pencil"></i>

                                </button>

                                <button
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal">

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

<!-- Редактирование пользователя -->

<div class="modal fade" id="editUserModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Редактировать пользователя
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
                            Имя
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Иван Петров">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            value="ivan@mail.ru">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Роль
                        </label>

                        <select class="form-select">

                            <option selected>
                                Администратор
                            </option>

                            <option>
                                Менеджер
                            </option>

                            <option>
                                Покупатель
                            </option>

                        </select>

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

<!-- Удаление пользователя -->

<div class="modal fade" id="deleteUserModal">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Удаление пользователя
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                Вы действительно хотите удалить пользователя?

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