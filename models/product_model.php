<?php


function getLatestSixProducts()
{
    global $conn;

    $sql = "SELECT products.*, brands.name AS brand_name, categories.name AS category_name
            FROM products
            JOIN brands ON products.brand_id = brands.id
            JOIN categories ON products.category_id = categories.id
            ORDER BY products.id DESC
            LIMIT 6";

    $statement = $conn->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
}


function getProductsByCategory($categoryId)
{
    global $conn;

    $sql = "SELECT products.*, brands.name AS brand_name
        FROM products
        JOIN brands ON products.brand_id = brands.id
        WHERE products.category_id = ?
        ORDER BY products.id DESC";

    $statement = $conn->prepare($sql);
    $statement->execute(array($categoryId));

    return $statement->fetchAll();
}
?>
