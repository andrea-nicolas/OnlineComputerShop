<?php

// Start session because login information is stored in $_SESSION.
session_start();

// 1. Database connection.
require_once 'config/database.php';

// 2. Models: all database queries are kept here.
require_once 'models/user_model.php';
require_once 'models/category_model.php';
require_once 'models/product_model.php';

// 3. Controllers: page work is kept here.
require_once 'controllers/home_controller.php';
require_once 'controllers/auth_controller.php';
require_once 'controllers/profile_controller.php';
require_once 'controllers/category_controller.php';

// If session ended but Remember Me cookie is valid, login again.
tryRememberLogin();

// By default, open the home page.
$page = 'home';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// Very simple page routing.
if ($page == 'home') {
    homeController();
} elseif ($page == 'register') {
    registerController();
} elseif ($page == 'login') {
    loginController();
} elseif ($page == 'logout') {
    logoutController();
} elseif ($page == 'profile') {
    profileController();
} elseif ($page == 'category') {
    categoryController();
} else {
    echo '<h2>Page not found</h2>';
    echo '<a href="index.php">Go to Home</a>';
}
?>
