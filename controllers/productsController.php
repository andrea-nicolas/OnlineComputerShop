<?php

    require "../models/db.php";

    $db = new mydb();
    $conn = $db->openConn();

    $products = $db->getAllProducts($conn);
?>