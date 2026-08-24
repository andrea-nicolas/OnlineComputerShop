<?php   
    /*session_start();

    if(empty($_SESSION["id"]))
    {
        header("Location: ../view/login.php");
        exit;
    }*/

    //verify qty
    $qtyErr = [];
    $productId = null;

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
            $db->insertToCarts(1, $productId, $quantity, $conn);
        }
    }
?>