<?php
require_once "../models/db.php";

$db = new mydb();
$conn = $db->openConn();

$userId = 1;
$orderMsg = "";
$shippingFee = 100;

$action = isset($_GET["action"]) ? $_GET["action"] : "";

if ($action == "add") {
    $productId = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    $result = $db->getProductById($productId, $conn);
    $stock = 0;
    while ($row = $result->fetch_assoc()) {
        $stock = $row["stock"];
    }

    if ($quantity > $stock) {
        echo "ERROR|Sorry, not enough products in stock.";
    } elseif ($quantity <= 0) {
        echo "ERROR|Invalid quantity number!";
    } else {
        $check = $db->checkCartItem($userId, $productId, $conn);
        if ($check && $check->num_rows > 0) {
            $row = $check->fetch_assoc();
            $newQty = $row["quantity"] + $quantity;
            $db->updateCartQuantity($userId, $productId, $newQty, $conn);
        } else {
            $db->insertToCarts($userId, $productId, $quantity, $conn);
        }

        $cartCount = $db->getCartCount($userId, $conn);
        echo "SUCCESS|" . $cartCount . "|Added to cart successfully!";
    }
    exit;
}

if ($action == "update" || $action == "remove") {
    $productId = (int)$_POST["product_id"];

    if ($action == "update") {
        $quantity = (int)$_POST["quantity"];
        if ($quantity > 0) {
            $db->updateCartQuantity($userId, $productId, $quantity, $conn);
        } else {
            $db->deleteCartItem($userId, $productId, $conn);
        }
    } else {
        $db->deleteCartItem($userId, $productId, $conn);
    }

    $cartResults = $db->getCartItemsByUserId($userId, $conn);
    $totalItems = 0;
    $subtotal = 0;

    while ($row = $cartResults->fetch_assoc()) {
        $totalItems += (int)$row["quantity"];
        $subtotal += ((float)$row["price"] * (int)$row["quantity"]);
    }

    $totalPrice = $subtotal > 0 ? ($subtotal + $shippingFee) : 0;
    echo $totalItems . "|" . $subtotal . "|" . $totalPrice;
    exit;
}

if (isset($_POST["checkout"])) {
    $cartCheck = $db->getCartItemsByUserId($userId, $conn);
    if (!$cartCheck || $cartCheck->num_rows == 0) {
        $orderMsg = "Your cart is empty.";
    } elseif (!isset($_POST["payment_method"])) {
        $orderMsg = "Please select a payment method.";
    } else {
        $cartSubtotal = 0;
        while ($row = $cartCheck->fetch_assoc()) {
            $cartSubtotal += ((float)$row["price"] * (int)$row["quantity"]);
        }
        $checkoutTotal = $cartSubtotal > 0 ? ($cartSubtotal + $shippingFee) : 0;

        $paymentMethod = $_POST["payment_method"];
        $db->createOrder($userId, $checkoutTotal, $paymentMethod, $conn);
        $orderMsg = "Order placed successfully!";
    }
}


$cartResults = $db->getCartItemsByUserId($userId, $conn);
$cartItems = [];
$totalItems = 0;
$subtotal = 0;

if ($cartResults) {
    while ($row = $cartResults->fetch_assoc()) {
        $row["subtotal"] = (float)$row["price"] * (int)$row["quantity"];
        $cartItems[] = $row;
        $totalItems += (int)$row["quantity"];
        $subtotal += $row["subtotal"];
    }
}

$displayShipping = $subtotal > 0 ? $shippingFee : 0;
$totalPrice = $subtotal > 0 ? ($subtotal + $shippingFee) : 0;
?>