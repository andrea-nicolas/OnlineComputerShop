<?php

require_once __DIR__ . "/bootstrap.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    echo json_encode(array(
        "ok"         => false,
        "message"    => "Your session has expired. Please refresh the page and try again.",
        "count"      => 0,
        "total_text" => money(0)
    ));
    exit;
}

$action = isset($_REQUEST["action"]) ? clean_input($_REQUEST["action"]) : "";

$response = array(
    "ok"         => false,
    "message"    => "",
    "count"      => 0,
    "total_text" => money(0),
    "cart_id"    => 0,
    "quantity"   => 0,
    "line_text"  => ""
);

function cart_lines($db, $conn, $userId)
{
    $lines  = array();
    $result = $db->getCartItems($conn, $userId);

    while ($row = $result->fetch_assoc()) {
        $lines[] = $row;
    }
    return $lines;
}

function cart_total($lines)
{
    $total = 0;
    foreach ($lines as $line) {
        $total = $total + (float) $line["price"] * (int) $line["quantity"];
    }
    return $total;
}

if (!$isLoggedIn) {

    $response["message"] = "You have to sign in before using the cart.";

} elseif (!$isCustomer) {

    $response["message"] = "Only customers have a cart.";

} else {

    $cartId = 0;
    if (isset($_POST["cart_id"]) && is_numeric($_POST["cart_id"])) {
        $cartId = (int) $_POST["cart_id"];
    }

    $lines = cart_lines($db, $conn, $userId);
    $line  = null;

    foreach ($lines as $row) {
        if ((int) $row["cart_id"] == $cartId) {
            $line = $row;
        }
    }

    if ($action == "update") {

        $quantity = 0;
        if (isset($_POST["quantity"]) && is_numeric($_POST["quantity"])) {
            $quantity = (int) $_POST["quantity"];
        }

        if ($line == null) {
            $response["message"] = "That item is not in your cart.";
        } elseif ($quantity < 1) {
            $response["message"] = "The quantity has to be at least 1. Use Remove to take the item out.";
        } elseif ($quantity > (int) $line["stock"]) {
            $response["message"] = "Only " . (int) $line["stock"] . " of " . $line["name"] . " are in stock.";
        } elseif ($db->updateCartQuantity($conn, $cartId, $userId, $quantity)) {

            $response["ok"]        = true;
            $response["message"]   = "Cart updated.";
            $response["cart_id"]   = $cartId;
            $response["quantity"]  = $quantity;
            $response["line_text"] = money((float) $line["price"] * $quantity);

        } else {
            $response["message"] = "The quantity could not be changed.";
        }

    } elseif ($action == "remove") {

        if ($line == null) {
            $response["message"] = "That item is not in your cart.";
        } elseif ($db->removeCartItem($conn, $cartId, $userId)) {
            $response["ok"]      = true;
            $response["message"] = "Item removed from your cart.";
            $response["cart_id"] = $cartId;
        } else {
            $response["message"] = "The item could not be removed.";
        }

    } else {

        $response["message"] = "Unknown action.";
    }

    $lines               = cart_lines($db, $conn, $userId);
    $response["count"]   = count($lines);
    $response["total_text"] = money(cart_total($lines));
}

echo json_encode($response);
