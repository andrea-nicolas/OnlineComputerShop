<?php
require_once "../models/db.php";

$db = new mydb();
$conn = $db->openConn();

$q = isset($_GET["q"]) ? $_GET["q"] : "";
$cat = isset($_GET["category"]) ? $_GET["category"] : "";
$brand = isset($_GET["brand"]) ? $_GET["brand"] : "";
$maxPrice = isset($_GET["max_price"]) ? $_GET["max_price"] : "";

$products = $db->searchAndFilterProducts($q, $cat, $brand, $maxPrice, $conn);

include "../views/productsSearched.php";