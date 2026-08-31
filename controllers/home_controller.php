<?php

function homeController()
{
    // Data needed by the home view.
    $pageTitle = 'Home';
    $categories = getAllMainCategories();
    $products = getLatestSixProducts();

    // Show the home page.
    require 'views/home/index.php';
}
?>
