<?php

include_once "../control/authcheck.php";
include_once "../model/db.php";

$db = new mydb();
$conn = $db->openConn();

$totalProducts = 0;
$totalCategories = 0;
$totalBrands = 0;
$inactiveProducts = 0;
$lowStockRows = array();

// ---- total products ----
$result = $db->countProducts($conn);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalProducts = $row["total"];
}

// ---- how many of them are switched off ----
$result = $db->countInactiveProducts($conn);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $inactiveProducts = $row["total"];
}

// ---- total categories ----
$result = $db->countCategories($conn);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalCategories = $row["total"];
}

// ---- total brands ----
$result = $db->countBrands($conn);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalBrands = $row["total"];
}

// ---- low stock products (stock less than 5) ----
$result = $db->getLowStockProducts($conn, 5);
while ($row = $result->fetch_assoc()) {
    $lowStockRows[] = $row;
}

$conn->close();

?>
