
<?php include __DIR__ . '/../../includes/admin_header.php'; ?>

<?php

    include __DIR__ . '/../../includes/db.php';

    $productsCount = pg_fetch_result(
        pg_query($conn, "SELECT COUNT(*) FROM products"),
        0,
        0
    );

    $categoriesCount = pg_fetch_result(
        pg_query($conn, "SELECT COUNT(*) FROM categories"),
        0,
        0
    );

    $usersCount = pg_fetch_result(
        pg_query($conn, "SELECT COUNT(*) FROM users"),
        0,
        0
    );

    $ordersCount = pg_fetch_result(
        pg_query($conn, "SELECT COUNT(*) FROM orders"),
        0,
        0
    );

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
        ORDER BY orders.created_at DESC
        LIMIT 5"
    );
?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 p-4">

            <div class="dashboard-banner">

                <h1>Панель управления</h1>

                <p>
                    Интернет-магазин электроники ElectroAdmin
                </p>

            </div>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card stat-card">

                        <div class="card-body">

                            <div class="stat-top">

                                <span class="stat-title">

                                    Товары

                                </span>

                                <i class="bi bi-box-seam stat-icon"></i>

                            </div>

                            <div class="stat-number">

                                <?= $productsCount ?>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card">

                        <div class="card-body">

                            <div class="stat-top">

                                <span class="stat-title">

                                    Категории

                                </span>

                                <i class="bi bi-grid stat-icon"></i>

                            </div>

                            <div class="stat-number">

                                <?= $categoriesCount ?>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card">

                        <div class="card-body">

                            <div class="stat-top">

                                <span class="stat-title">

                                    Пользователи

                                </span>

                                <i class="bi bi-people stat-icon"></i>

                            </div>

                            <div class="stat-number">

                                <?= $usersCount ?>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card">

                        <div class="card-body">

                            <div class="stat-top">

                                <span class="stat-title">

                                    Заказы

                                </span>

                                <i class="bi bi-bag-check stat-icon"></i>

                            </div>

                            <div class="stat-number">

                                <?= $ordersCount ?>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <h3 class="section-title">
                Последние заказы
            </h3>

            <div class="card shadow">

                <div class="card-body">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Клиент</th>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Статус</th>
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

                                <tr class="order-row"
                                    onclick="window.location.href='orders.php?edit=<?= $order["id"] ?>'">

                                    <td>

                                        #<?= $order["id"] ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($order["name"]) ?>

                                    </td>

                                    <td>

                                        <?= date("d.m.Y", strtotime($order["created_at"])) ?>

                                    </td>

                                    <td>

                                        <?= number_format($order["total_price"], 0, ",", " ") ?> ₽

                                    </td>

                                    <td>

                                        <span class="status-badge <?= $statusClass ?>">

                                            <?= htmlspecialchars($order["status"]) ?>

                                        </span>

                                    </td>

                                </tr>

                                <?php endwhile; ?>

                            </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>