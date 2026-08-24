<?php

    require "../models/db.php";

    $db = new mydb();
    $conn = $db->openConn();

    //fill products page
    $products = $db->getAllProducts($conn);

    //verify qty
    $qtyErr = "";
    $productId = null;

    if (isset($_REQUEST["add"])) 
    {
        $productId = (int)$_REQUEST["product_id"];
        $quantity = (int)$_REQUEST["quantity"];

        $result = $db->getProductById($productId, $conn);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $stock = $row["stock"];
            }

            if ($quantity <= 0) {
                $qtyErr = "Please select appropriate quantity.";
            } elseif ($quantity > $stock) {
                $qtyErr = "Sorry, not enough products in stock.";
            } else {
                $qtyErr = "Added to cart successfully!";
            }
        } else {
            $qtyErr = "Product not found.";
        }
    }
?>