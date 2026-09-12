<?php

require_once __DIR__ . "/bootstrap.php";

$orderId     = 0;
$order       = null;
$orderItems  = array();
$accessError = "";
$justPlaced  = (isset($_GET["msg"]) && $_GET["msg"] == "placed");

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $orderId = (int) $_GET["id"];
}

if ($orderId <= 0) {

    $accessError = "No order was selected.";

} else {

    $result = $db->getOrderById($conn, $orderId);

    if ($result->num_rows < 1) {
        $accessError = "That order does not exist.";
    } else {
        $order = $result->fetch_assoc();

        if (!$isLoggedIn) {
            $accessError = "You have to log in to view an order.";
            $order = null;
        } elseif (!$isAdmin && (int) $order["user_id"] != $userId) {
            $accessError = "This order belongs to another customer.";
            $order = null;
        } else {
            $result = $db->getOrderItems($conn, $orderId);

            while ($row = $result->fetch_assoc()) {
                $orderItems[] = $row;
            }
        }
    }
}
