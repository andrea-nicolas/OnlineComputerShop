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
    }
?>