<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../"includes/site_header.php';

$id = (int)$_GET["id"];

$result = pg_query_params(
    $conn,
    "SELECT *
     FROM orders
     WHERE id = $1
     AND user_id = $2",
    array(
        $id,
        $_SESSION["user_id"]
    )
);

$order = pg_fetch_assoc($result);

if (!$order)
{
    header("Location: orders.php");
    exit;
}

$result = pg_query_params(
    $conn,
    "SELECT
        oi.quantity,
        oi.price,
        p.id,
        p.name,
        p.image
    FROM order_items oi
    JOIN products p
        ON oi.product_id = p.id
    WHERE oi.order_id = $1",
    array($id)
);

$items = [];

while ($row = pg_fetch_assoc($result))
{
    $row["sum"] = $row["price"] * $row["quantity"];

    $items[] = $row;
}
?>

<section class="checkout-section py-5">

    <div class="container">

        <h1 class="mb-4">

            Заказ №<?= $order["id"] ?>

        </h1>

        <div class="row">

            <div class="col-lg-8">

            <?php foreach ($items as $item): ?>

                <div class="cart-item">

                    <div class="cart-image">

                        <img
                            src="img/products/<?= htmlspecialchars($item["image"]) ?>"
                            alt="<?= htmlspecialchars($item["name"]) ?>">

                    </div>

                    <div class="cart-info">

                        <h5>

                            <?= htmlspecialchars($item["name"]) ?>

                        </h5>

                        <p class="text-muted">

                            <?= number_format($item["price"],0,","," ") ?> ₽

                        </p>

                        <small>

                            Количество:
                            <strong><?= $item["quantity"] ?></strong>

                        </small>

                    </div>

                    <div class="cart-right">

                        <h5>

                            <?= number_format($item["sum"],0,","," ") ?> ₽

                        </h5>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="col-lg-4">

            <div class="cart-summary sticky-top">

                <h3>

                    <i class="bi bi-bag-check-fill text-warning me-2"></i>

                    Информация

                </h3>

                <hr>

                <p class="text-muted mb-1">

                    Статус

                </p>

                <span class="badge bg-warning text-dark">

                    <?= htmlspecialchars($order["status"]) ?>

                </span>

                <hr>

                    <p class="text-muted mb-1">

                        Дата оформления

                    </p>

                    <strong>

                        <?= date("d.m.Y H:i", strtotime($order["created_at"])) ?>

                    </strong>

                <hr>

                    <p class="text-muted mb-1">

                        Получатель

                    </p>

                    <strong>

                        <?= htmlspecialchars($_SESSION["user_name"]) ?>

                    </strong>

                <hr>

                    <p class="text-muted mb-1">

                        Итого

                    </p>

                    <h2 class="fw-bold">

                        <?= number_format($order["total_price"],0,","," ") ?> ₽

                    </h2>

                    <a
                        href="orders.php"
                        class="btn btn-outline-dark w-100 mt-4">

                        <i class="bi bi-arrow-left me-2"></i>

                        К моим заказам

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>