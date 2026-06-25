<?php

session_start();

$id = (int)$_POST["product_id"];

if (!isset($_SESSION["cart"]))
{
    $_SESSION["cart"] = [];
}

if (isset($_SESSION["cart"][$id]))
{
    $_SESSION["cart"][$id]["quantity"]++;
}
else
{
    $_SESSION["cart"][$id] = [

        "quantity" => 1,

        "selected" => true

    ];
}

header("Location: cart.php");
exit;