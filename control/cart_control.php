<?php

require_once __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    $csrfFailed = true;
    $_POST = array();
} else {
    $csrfFailed = false;
}

$cartItems     = array();
$cartTotal     = 0;
$paymentMethod = "";
$paymentError  = "";
$formError     = "";
$successMsg    = "";

if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "updated") {
        $successMsg = "Cart updated.";
    } elseif ($_GET["msg"] == "removed") {
        $successMsg = "Item removed from your cart.";
    }
}

if (!$isLoggedIn) {
    redirect(link_to("/view/login.php?msg=required"));
}

$canCheckout = $isCustomer;

if ($canCheckout) {

    if (isset($_POST["update_qty"])) {

        $cartId   = 0;
        $quantity = 0;

        if (isset($_POST["cart_id"]) && is_numeric($_POST["cart_id"])) {
            $cartId = (int) $_POST["cart_id"];
        }
        if (isset($_POST["quantity"]) && is_numeric($_POST["quantity"])) {
            $quantity = (int) $_POST["quantity"];
        }

        if ($cartId <= 0) {
            $formError = "That cart item does not exist.";
        } elseif ($quantity < 1) {
            $formError = "The quantity has to be at least 1. Use Remove to take the item out.";
        } else {

            $line = null;
            $res  = $db->getCartItems($conn, $userId);

            while ($row = $res->fetch_assoc()) {
                if ((int) $row["cart_id"] == $cartId) {
                    $line = $row;
                }
            }

            if ($line == null) {
                $formError = "That item is not in your cart.";
            } elseif ($quantity > (int) $line["stock"]) {
                $formError = "Only " . (int) $line["stock"] . " of " . $line["name"] . " are in stock.";
            } elseif ($db->updateCartQuantity($conn, $cartId, $userId, $quantity)) {
                redirect(link_to("/view/cart.php?msg=updated"));
            } else {
                $formError = "The quantity could not be changed.";
            }
        }
    }

    if (isset($_POST["remove_item"])) {

        $cartId = 0;
        if (isset($_POST["cart_id"]) && is_numeric($_POST["cart_id"])) {
            $cartId = (int) $_POST["cart_id"];
        }

        if ($cartId <= 0) {
            $formError = "That cart item does not exist.";
        } elseif ($db->removeCartItem($conn, $cartId, $userId)) {
            redirect(link_to("/view/cart.php?msg=removed"));
        } else {
            $formError = "That item is not in your cart.";
        }
    }

    if (isset($_POST["place_order"])) {

        $paymentMethod = isset($_POST["payment_method"]) ? clean_input($_POST["payment_method"]) : "";

        $cartItems = array();
        $result    = $db->getCartItems($conn, $userId);

        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }

        if (count($cartItems) == 0) {
            $formError = "Your cart is empty, so there is nothing to order.";
        }

        if ($formError == "") {
            $allowed = payment_methods();

            if (empty($paymentMethod)) {
                $paymentError = "Please choose a payment method.";
            } elseif (!array_key_exists($paymentMethod, $allowed)) {
                $paymentError = "That payment method is not available.";
            }
        }

        if ($formError == "" && $paymentError == "") {
            foreach ($cartItems as $item) {
                $quantity = (int) $item["quantity"];
                $stock    = (int) $item["stock"];

                if ($quantity < 1) {
                    $formError = "The quantity for " . $item["name"] . " is not valid.";
                    break;
                }
                if ($quantity > $stock) {
                    $formError = "Only " . $stock . " of " . $item["name"]
                               . " are left in stock, but your cart has " . $quantity . ".";
                    break;
                }
            }
        }

        if ($formError == "" && $paymentError == "") {

            $total = 0;
            foreach ($cartItems as $item) {
                $total = $total + (float) $item["price"] * (int) $item["quantity"];
            }

            $total = round($total);

            $orderId = $db->placeOrder($conn, $userId, $cartItems, $total, $paymentMethod);

            if ($orderId > 0) {
                redirect(link_to("/view/order_confirmation.php?id=" . $orderId . "&msg=placed"));
            } else {
                $formError = "The order could not be placed. Nothing was saved. Please try again.";
            }
        }
    }

    $cartItems = array();
    $result    = $db->getCartItems($conn, $userId);

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $cartTotal   = $cartTotal + (float) $row["price"] * (int) $row["quantity"];
    }
}

if ($csrfFailed) {
    $formError = "Your session has expired. Please refresh the page and try again.";
}
