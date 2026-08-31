<?php
class mydb
{
    function openConn()
    {
        return new mysqli("localhost", "root", "", "onlinecomputershop");
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

    function getCartItemsByUserId($userId, $conn)
    {
        $sql = "SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.name, p.price, p.image_path 
                FROM carts c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = $userId";
        return $conn->query($sql);
    }

    function createOrder($userId, $totalAmount, $paymentMethod, $conn)
    {
        $sql = "INSERT INTO orders (user_id, total_amount, payment_method, status) 
                VALUES ($userId, $totalAmount, '$paymentMethod', 'pending')";
        $conn->query($sql);

        $deleteSql = "DELETE FROM carts WHERE user_id = $userId";
        return $conn->query($deleteSql);
    }

    function getAllCategories($conn)
    {
        $sql = "SELECT * FROM categories";
        return $conn->query($sql);
    }

    function getAllBrands($conn)
    {
        $sql = "SELECT DISTINCT name FROM brands ORDER BY name ASC";
        return $conn->query($sql);
    }

    function getProductsByCategory($categoryName, $conn)
    {
        $sql = "SELECT p.* FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE c.name = '$categoryName'";
        return $conn->query($sql);
    }

    function getProductsByBrand($brandName, $conn)
    {
        $sql = "SELECT p.* FROM products p 
                JOIN brands b ON p.brand_id = b.id 
                WHERE b.name = '$brandName'";
        return $conn->query($sql);
    }
}
?>
