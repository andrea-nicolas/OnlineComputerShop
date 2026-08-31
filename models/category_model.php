<?php

// Get all main categories for the category bar.
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

// Get one category by id.
function getCategoryById($categoryId)
{
    global $conn;

    $sql = "SELECT * FROM categories WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($categoryId));

    return $statement->fetch();
}
?>
