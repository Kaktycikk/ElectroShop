<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit;
}

include "includes/db.php";
include "includes/site_header.php";

$result = pg_query_params(
    $conn,
    "SELECT id, total_price, status, created_at
     FROM orders
     WHERE user_id = $1
     ORDER BY created_at DESC",
    array($_SESSION["user_id"])
);

?>

<section class="py-5">

<div class="container">

    <h1 class="mb-4">

        Мои заказы

    </h1>

    <?php if (pg_num_rows($result) == 0): ?>

        <div class="alert alert-info">

            Вы еще не оформили ни одного заказа.

        </div>

    <?php else: ?>

    <?php while ($order = pg_fetch_assoc($result)): ?>
        <div class="card shadow-sm mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5>

                            <i class="bi bi-bag-check-fill text-warning me-2"></i>

                            Заказ №<?= $order["id"] ?>

                        </h5>

                        <p class="text-muted mb-1">

                            <?= date("d.m.Y H:i", strtotime($order["created_at"])) ?>

                        </p>

                        <?php

                        $statusClass = match ($order["status"])
                        {
                            "Новый" => "bg-warning text-dark",
                            "В обработке" => "bg-primary",
                            "Отправлен" => "bg-info text-dark",
                            "Доставлен" => "bg-success",
                            "Отменен" => "bg-danger",
                            default => "bg-secondary"
                        };

                        ?>

                        <span class="badge <?= $statusClass ?>">

                            <?= htmlspecialchars($order["status"]) ?>

                        </span>

                    </div>

                    <div class="text-end">

                        <h4>

                            <?= number_format($order["total_price"], 0, ",", " ") ?> ₽

                        </h4>

                        <a
                            href="order.php?id=<?= $order["id"] ?>"
                            class="btn btn-outline-warning btn-sm">

                            Подробнее

                        </a>

                    </div>

                </div>

            </div>

        </div>
        <?php endwhile; ?>

        <?php endif; ?>

    </div>

</section>

<?php include "includes/site_footer.php"; ?>