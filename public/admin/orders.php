<?php

$orders = [
    [
        'id' => 1001,
        'customer' => 'Иван Петров',
        'product' => 'iPhone 16 Pro',
        'amount' => '89 990 ₽',
        'date' => '15.06.2026',
        'status' => 'Новый'
    ],
    [
        'id' => 1002,
        'customer' => 'Анна Смирнова',
        'product' => 'RTX 5070',
        'amount' => '75 990 ₽',
        'date' => '16.06.2026',
        'status' => 'В обработке'
    ],
    [
        'id' => 1003,
        'customer' => 'Дмитрий Иванов',
        'product' => 'MacBook Air M5',
        'amount' => '119 990 ₽',
        'date' => '17.06.2026',
        'status' => 'Доставлен'
    ]
];

include __DIR__ . '/../../includes/admin_header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">

                <h1>Заказы</h1>

                <p>
                    Управление заказами интернет-магазина
                </p>

            </div>

            <div class="table-wrapper">

                <table class="table">

                    <thead>

                        <tr>

                            <th>№ Заказа</th>
                            <th>Клиент</th>
                            <th>Товар</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                            <th>Статус</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($orders as $order): ?>

                        <tr>

                            <td>#<?= $order['id']; ?></td>

                            <td><?= $order['customer']; ?></td>

                            <td><?= $order['product']; ?></td>

                            <td><?= $order['amount']; ?></td>

                            <td><?= $order['date']; ?></td>

                            <td><?= $order['status']; ?></td>

                            <td>

                                <button
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editOrderModal">

                                    <i class="bi bi-pencil"></i>

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

<!-- Редактирование заказа -->

<div class="modal fade" id="editOrderModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Изменить статус заказа
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
                            Статус заказа
                        </label>

                        <select class="form-select">

                            <option selected>
                                Новый
                            </option>

                            <option>
                                В обработке
                            </option>

                            <option>
                                Отправлен
                            </option>

                            <option>
                                Доставлен
                            </option>

                            <option>
                                Отменён
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

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>