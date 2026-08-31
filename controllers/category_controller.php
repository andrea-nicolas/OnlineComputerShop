<?php

function categoryController()
{
    $categoryId = 0;

    if (isset($_GET['id'])) {
        $categoryId = (int) $_GET['id'];
    }

    $category = getCategoryById($categoryId);

    // If the category does not exist, go back to home.
    if (!$category) {
        $_SESSION['message'] = 'Category not found.';
        header('Location: index.php');
        exit;
    }

    $pageTitle = $category['name'];
    $categories = getAllMainCategories();
    $products = getProductsByCategory($categoryId);

    require 'views/category/show.php';
}
?>
