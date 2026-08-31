<?php
require_once "../models/db.php";

$db = new mydb();
$conn = $db->openConn();

$categories = $db->getAllCategories($conn);
$brands = $db->getAllBrands($conn);

$pageTitle = "All Products";

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $selectedCategory = $_GET['category'];
    $products = $db->getProductsByCategory($selectedCategory, $conn);
    $pageTitle = "Category: " . $selectedCategory;
} elseif (isset($_GET['brand']) && !empty($_GET['brand'])) {
    $selectedBrand = $_GET['brand'];
    $products = $db->getProductsByBrand($selectedBrand, $conn);
    $pageTitle = "Brand: " . $selectedBrand;
} else {
    $products = $db->getAllProducts($conn);
}
?>
