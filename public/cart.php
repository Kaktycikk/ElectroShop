<?php

session_start();

if (isset($_GET["action"]) && isset($_GET["id"]))
{
    $id = (int)$_GET["id"];

    if (isset($_SESSION["cart"][$id]))
    {
        switch ($_GET["action"])
        {
            case "plus":

                $_SESSION["cart"][$id]["quantity"]++;

                break;

            case "minus":

                $_SESSION["cart"][$id]["quantity"]--;

                if ($_SESSION["cart"][$id]["quantity"] <= 0)
                {
                    unset($_SESSION["cart"][$id]);
                }

                break;

            case "remove":

                unset($_SESSION["cart"][$id]);

                break;

            case "toggle":

                $_SESSION["cart"][$id]["selected"] = !$_SESSION["cart"][$id]["selected"];

                break;
        }
    }

    header("Location: cart.php#cart");
    
    exit;
}


include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/site_header.php';

$cart = $_SESSION['cart'] ?? [];

$total = 0;

?>

<?php if (isset($_SESSION["order_success"])): ?>

    <div class="container mt-4">

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill me-2"></i>

            <?= $_SESSION["order_success"] ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    </div>

    <?php unset($_SESSION["order_success"]); ?>

<?php endif; ?>

<?php if (isset($_SESSION["order_error"])): ?>

<div class="container mt-4">

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        <?= htmlspecialchars($_SESSION["order_error"]) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

</div>

<?php unset($_SESSION["order_error"]); ?>

<?php endif; ?>

<section id="cart" class="cart-section py-5">

<div class="container">

    <h1 class="mb-4">

        Корзина

    </h1>

    <div class="card shadow">

        <div class="card-body">

            <?php if (empty($cart)): ?>

                <p>

                    Корзина пуста

                </p>

            <?php else: ?>

                <div class="row">

                    <div class="col-lg-8">

                        <?php foreach ($cart as $id => $item): ?>

                            <?php

                            if (!is_array($item))
                            {
                                continue;
                            }

                            $quantity = $item["quantity"];
                            $selected = $item["selected"];

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

                            $sum = $product["price"] * $quantity;

                            if ($selected)
                            {
                                $total += $sum;
                            }

                            ?>

                            <div class="cart-item">

                                <div class="cart-check">

                                    <form method="GET">

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="toggle">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $id ?>">

                                        <input
                                            type="checkbox"
                                            name="selected"
                                            class="form-check-input"
                                            onchange="this.form.submit()"
                                            <?= $selected ? "checked" : "" ?>>

                                    </form>

                                </div>

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

                                        <?= number_format($product["price"], 0, ",", " ") ?> ₽

                                    </p>

                                    <div class="cart-quantity">

                                        <a
                                            href="cart.php?action=minus&id=<?= $id ?>#cart"
                                            class="btn btn-light btn-sm">

                                            −

                                        </a>

                                        <span class="mx-2">

                                            <?= $quantity ?>

                                        </span>

                                        <a
                                            href="cart.php?action=plus&id=<?= $id ?>#cart"
                                            class="btn btn-light btn-sm">

                                            +

                                        </a>

                                    </div>

                                </div>

                                <div class="cart-right">

                                    <h5>

                                        <?= number_format($sum, 0, ",", " ") ?> ₽

                                    </h5>

                                    <a
                                        href="cart.php?action=remove&id=<?= $id ?>#cart"
                                        class="cart-remove"
                                        title="Удалить">

                                        <i class="bi bi-x-lg"></i>

                                    </a>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <div class="col-lg-4">

                        <div class="cart-summary">

                            <h3>

                                Ваш заказ

                            </h3>

                            <hr>

                            <p>

                                Товаров:
                                <?php

                                $count = 0;

                                foreach ($cart as $item)
                                {
                                    if ($item["selected"])
                                    {
                                        $count += $item["quantity"];
                                    }
                                }

                                ?>

                                <strong><?= $count ?></strong>

                            </p>

                            <h4>

                                <?= number_format($total,0,","," ") ?> ₽

                            </h4>

                            <?php if ($count > 0): ?>

                                <a href="checkout.php"
                                class="btn btn-warning w-100 mt-3">

                                    Оформить заказ

                                </a>

                            <?php else: ?>

                                <button
                                    class="btn btn-warning w-100 mt-3"
                                    disabled>

                                    Выберите товары

                                </button>

                            <?php endif; ?>

                            <a href="catalog.php"
                            class="btn btn-outline-dark w-100 mt-2">

                                Продолжить покупки

                            </a>

                        </div>

                    </div>

                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>
