<?php

include __DIR__ . '/../../includes/admin_header.php';

include __DIR__ . '/../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $orderId = (int)$_POST["order_id"];
    $status = trim($_POST["status"]);

    pg_query_params(
        $conn,
        "UPDATE orders
         SET status = $1
         WHERE id = $2",
        array(
            $status,
            $orderId
        )
    );

    header("Location: orders.php");
    exit;
}

$editOrderId = isset($_GET["edit"]) ? (int)$_GET["edit"] : null;

$orders = pg_query(
    $conn,
    "SELECT
        orders.id,
        users.name,
        orders.total_price,
        orders.status,
        orders.created_at
    FROM orders
    JOIN users
        ON users.id = orders.user_id
    ORDER BY orders.created_at DESC"
);

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

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>№ Заказа</th>
                            <th>Клиент</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                            <th>Статус</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($order = pg_fetch_assoc($orders)): ?>

                            <?php

                                    $statusClass = match ($order["status"])
                                    {
                                        "Новый" => "status-new",
                                        "В обработке" => "status-processing",
                                        "Отправлен" => "status-shipped",
                                        "Доставлен" => "status-success",
                                        "Отменён" => "status-danger",
                                        "Отменен" => "status-danger",
                                        default => "status-new"
                                    };

                                ?>

                        <tr>

                            <td>#<?= $order['id']; ?></td>

                            <td> <?= htmlspecialchars($order["name"]) ?></td>

                            <td> <?= number_format($order["total_price"],0,","," ") ?> ₽</td>

                            <td> <?= date("d.m.Y", strtotime($order["created_at"])) ?></td>

                            <td>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($order["status"]) ?>
                                </span>
                            </td>

                            <td>

                                <button
                                    class="edit-btn edit-order-btn"

                                    data-order="<?= $order["id"] ?>"
                                    data-id="<?= $order["id"] ?>"
                                    data-status="<?= htmlspecialchars($order["status"]) ?>"

                                    data-bs-toggle="modal"
                                    data-bs-target="#editOrderModal">

                                    <i class="bi bi-pencil-fill"></i>

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

                <form method="POST">

                    <input
                        type="hidden"
                        name="order_id"
                        id="orderId">

                        <div class="mb-3">

                            <label class="form-label">
                                Статус заказа
                            </label>

                            <select
                            class="form-select"
                            id="orderStatus"
                            name="status">

                                <option>
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

                        <div class="modal-footer border-0 pt-2">

                            <button
                                type="button"
                                class="btn btn-modal-cancel"
                                data-bs-dismiss="modal">

                                <i class="bi bi-x-lg me-2"></i>

                                Отмена

                            </button>

                            <button
                                type="submit"
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

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>