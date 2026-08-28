<?php

// All database work for the admin panel is done by this class.
// Queries that take user input use prepared statements (prepare + bind_param).
// Queries with no user input use $conn->query() directly.

class mydb
{

    // ================= CONNECTION =================

    function openConn()
    {
        $conn = new mysqli("localhost", "root", "", "onlinecomputershop");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }


    // ================= USERS (login only) =================

    function findUserByEmail($conn, $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ================= DASHBOARD =================

    function countProducts($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM products";
        return $conn->query($sql);
    }

    function countCategories($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM categories";
        return $conn->query($sql);
    }

    function countBrands($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM brands";
        return $conn->query($sql);
    }

    function getLowStockProducts($conn, $limit)
    {
        $sql = "SELECT id, name, stock FROM products WHERE stock < ? ORDER BY stock ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
}

?>
