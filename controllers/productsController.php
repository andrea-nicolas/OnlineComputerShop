<?php
    $qtyErr = "";
    $productId = null;

    if (isset($_REQUEST["mysubmit"])) {
        $stockData = [
            '1' => 12,
            '2' => 0,
            '3' => 1,
            '4' => 1
        ];

        $productId = $_REQUEST["product_id"];
        $quantity = (int)$_REQUEST["quantity"];

        if ($quantity <= 0) 
        {
            $qtyErr = "Please select appropriate quantity.";
        } 
        elseif ($quantity > $stockData[$productId]) 
            {
            $qtyErr = "Sorry, not enough products in stock.";
        } 
        else 
        {
            $qtyErr = "Added to cart successfully!";
        }
    }
?>