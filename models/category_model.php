<?php

function getAllMainCategories()
{
    global $conn;

    $sql = "SELECT * FROM categories
            WHERE parent_id IS NULL
            ORDER BY name ASC";

    $statement = $conn->prepare($sql);
    $statement->execute();

    return $statement->fetchAll();
}


function getCategoryById($categoryId)
{
    global $conn;

    $sql = "SELECT * FROM categories WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($categoryId));

    return $statement->fetch();
}
?>
