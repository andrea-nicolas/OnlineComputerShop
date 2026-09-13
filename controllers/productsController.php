<?php
require_once "../models/db.php";

$db = new mydb();
$conn = $db->openConn();

$userId = 1;

$categories = $db->getAllCategories($conn);
$brands = $db->getAllBrands($conn);
$cartCount = $db->getCartCount($userId, $conn);

$pageTitle = "All Products";
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

if (isset($_GET['q']) && !empty($_GET['q'])) 
{
    $products = $db->searchAndFilterProducts($_GET['q'], "", "", "", $conn);
    $pageTitle = "Search: " . $_GET['q'];
} 
elseif (isset($_GET['category']) && !empty($_GET['category'])) 
{
    $selectedCategory = $_GET['category'];
    $products = $db->getProductsByCategory($selectedCategory, $conn);
    $pageTitle = "Category: " . $selectedCategory;
} 
elseif (isset($_GET['brand']) && !empty($_GET['brand'])) 
{
    $selectedBrand = $_GET['brand'];
    $products = $db->getProductsByBrand($selectedBrand, $conn);
    $pageTitle = "Brand: " . $selectedBrand;
} 
else 
{
    $products = $db->getAllProducts($conn);
}
?>