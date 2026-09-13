<?php

require_once __DIR__ . "/bootstrap.php";

$products = array();

$result = $db->getAllProducts($conn);

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
