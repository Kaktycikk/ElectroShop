<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../includes/db.php';

$cart = $_SESSION["cart"] ?? [];

$selectedProducts = [];
$total = 0;
$count = 0;

foreach ($cart as $id => $item)
{
    if (!$item["selected"])
    {
        continue;
    }

    $result = pg_query_params(
        $conn,
        "SELECT * FROM products WHERE id = $1",
        array($id)
    );

    $product = pg_fetch_assoc($result);

    if (!$product)
    {
        continue;
    }

    $product["quantity"] = $item["quantity"];
    $product["sum"] = $product["price"] * $item["quantity"];

    $selectedProducts[] = $product;

    $total += $product["sum"];
    $count += $item["quantity"];
}

if (empty($selectedProducts))
{
    header("Location: cart.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (!pg_query($conn, "BEGIN"))
    {
        die("Ошибка начала транзакции.");
    }

    $result = pg_query_params(
        $conn,
        "INSERT INTO orders (user_id, total_price, status)
         VALUES ($1, $2, $3)
         RETURNING id",
        array(
            $_SESSION["user_id"],
            $total,
            "Новый"
        )
    );

    if (!$result)
    {
        pg_query($conn, "ROLLBACK");
        die("Не удалось создать заказ.");
    }

    $order = pg_fetch_assoc($result);
    $orderId = $order["id"];

    foreach ($selectedProducts as $product)
    {
        $result = pg_query_params(
            $conn,
            "INSERT INTO order_items
            (order_id, product_id, quantity, price)
            VALUES ($1, $2, $3, $4)",
            array(
                $orderId,
                $product["id"],
                $product["quantity"],
                $product["price"]
            )
        );

        if (!$result)
        {
            pg_query($conn, "ROLLBACK");
            die("Не удалось добавить товары в заказ.");
        }
    }

    foreach ($selectedProducts as $product)
    {
        $result = pg_query_params(
            $conn,
            "UPDATE products
             SET stock = stock - $1
             WHERE id = $2
             AND stock >= $1",
            array(
                $product["quantity"],
                $product["id"]
            )
        );

        if (!$result)
        {
            pg_query($conn, "ROLLBACK");
            die("Не удалось обновить остаток товара.");
        }

        if (pg_affected_rows($result) == 0)
        {
            pg_query($conn, "ROLLBACK");

            $_SESSION["order_error"] =
                "Недостаточно товара «{$product["name"]}» на складе.";

            header("Location: cart.php");
            exit;
        }
    }

    foreach ($selectedProducts as $product)
    {
        unset($_SESSION["cart"][$product["id"]]);
    }

    if (!pg_query($conn, "COMMIT"))
    {
        pg_query($conn, "ROLLBACK");
        die("Не удалось завершить оформление заказа.");
    }

    $_SESSION["order_success"] =
        "Заказ №{$orderId} успешно оформлен!";

    header("Location: cart.php");
    exit;
}

include __DIR__ . '/../includes/site_header.php';

?>

<section class="checkout-section py-5">

<div class="container">

    <h1 class="mb-4">

        Оформление заказа

    </h1>

    <div class="row">

        <!-- Товары -->

        <div class="col-lg-8">

            <?php foreach ($selectedProducts as $product): ?>

                <div class="cart-item">

                    <div class="cart-image">

                        <img
                            src="img/products/<?= htmlspecialchars($product["image"]) ?>"
                            alt="<?= htmlspecialchars($product["name"]) ?>">

                    </div>

                    <div class="cart-info">

                        <h5>

                            <?= htmlspecialchars($product["name"]) ?>

                        </h5>

                        <p class="text-muted mb-2">

                            <?= number_format($product["price"],0,","," ") ?> ₽

                        </p>

                        <small>

                            Количество:
                            <strong><?= $product["quantity"] ?></strong>

                        </small>

                    </div>

                    <div class="cart-right">

                        <h5>

                            <?= number_format($product["sum"],0,","," ") ?> ₽

                        </h5>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <!-- Правая колонка -->

        <div class="col-lg-4">

            <div class="cart-summary sticky-top">

                <h3>

                    <i class="bi bi-bag-check-fill text-warning me-2"></i>

                    Ваш заказ

                </h3>

                <hr>

                <div class="d-flex align-items-center mb-3">

                    <i class="bi bi-person-circle fs-2 text-warning me-3"></i>

                    <div>

                        <strong>

                            <?= htmlspecialchars($_SESSION["user_name"]) ?>

                        </strong>

                        <div class="text-muted small">

                            <?= htmlspecialchars($_SESSION["user_email"]) ?>

                        </div>

                    </div>

                </div>

                <hr>

                <p>

                    Товаров

                </p>

                    <strong>

                        <?= $count ?> шт.

                    </strong>

                <hr>

                <p class="text-muted mb-1">

                    Итого к оплате

                </p>

                <h2 class="fw-bold mb-3">

                    <?= number_format($total,0,","," ") ?> ₽

                </h2>

                <div class="alert alert-light border mt-3 mb-0">

                    <i class="bi bi-info-circle text-warning me-2"></i>

                    После подтверждения заказ получит статус
                    <strong>«Новый»</strong>
                    и появится в истории заказов.

                </div>

                <form method="POST">

                    <button
                        class="btn btn-warning btn-lg w-100 mt-3"
                        type="submit">
                        <i class="bi bi-check-circle-fill me-2"></i>

                        Подтвердить заказ

                    </button>

                </form>

                <a
                    href="cart.php"
                    class="btn btn-outline-dark w-100 mt-2">

                    Вернуться в корзину

                </a>

            </div>

        </div>

    </div>

</div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>