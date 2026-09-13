<?php

function homeController()
{
    
    $pageTitle = 'Home';
    $categories = getAllMainCategories();
    $products = getLatestSixProducts();

    
    require 'views/home/index.php';
}
?>
