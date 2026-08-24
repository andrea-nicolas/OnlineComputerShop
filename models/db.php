<?php
    class mydb
    { 
        function openConn()
        {
            return new mysqli("localhost","root","","onlinecomputershop");
        }

        function getAllProducts($conn)
        {
            $sql = "SELECT * FROM products";
            return $conn->query($sql);
        }

        function getProductById($productId, $conn)
        {
            $sql = "SELECT stock FROM products WHERE id = $productId";
            return $conn->query($sql);
        }

        function insertToCarts($userId, $productId, $quantity, $conn)
        {
            $sql = "INSERT INTO carts (user_id, product_id, quantity) VALUES ($userId, $productId, $quantity)";
            return $conn->query($sql);
        }

    }
?>