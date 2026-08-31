<?php   
require_once "../models/db.php";
$db = new mydb();
$conn = $db->openConn();

$userId = 1;
$qtyErr = [];
$orderMsg = "";

if (isset($_POST["addtocart"])) 
{
    $productId = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    $result = $db->getProductById($productId, $conn);

    while ($row = $result->fetch_assoc()) 
    {
        $stock = $row["stock"];
    }
    if ($quantity > $stock) 
    {
        $qtyErr[$productId] = "Sorry, not enough products in stock.";
    } 
    else if ($quantity <= 0)
    {
        $qtyErr[$productId] = "Invalid quantity number!";
    }
    else
    {
        $qtyErr[$productId] = "Added to cart successfully!";
        $db->insertToCarts($userId, $productId, $quantity, $conn);
    }
}

$cartResults = $db->getCartItemsByUserId($userId, $conn);
$cartItems = [];
$totalItems = 0;
$subtotal = 0;
$shippingFee = 100;

if ($cartResults) {
    while ($row = $cartResults->fetch_assoc()) {
        $cartItems[] = $row;
        $totalItems += $row["quantity"];
        $subtotal += ($row["price"] * $row["quantity"]);
    }
}

$totalPrice = $subtotal > 0 ? ($subtotal + $shippingFee) : 0;

if (isset($_POST["checkout"])) 
{
    $paymentMethod = $_POST["payment_method"];
    $db->createOrder($userId, $totalPrice, $paymentMethod, $conn);
    $orderMsg = "Order placed successfully!";
    
    $cartItems = [];
    $totalItems = 0;
    $subtotal = 0;
    $totalPrice = 0;
}
?>
