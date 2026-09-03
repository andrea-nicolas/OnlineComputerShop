<?php


session_start();


require_once 'config/database.php';


require_once 'models/user_model.php';
require_once 'models/category_model.php';
require_once 'models/product_model.php';


require_once 'controllers/home_controller.php';
require_once 'controllers/auth_controller.php';
require_once 'controllers/profile_controller.php';
require_once 'controllers/category_controller.php';


tryRememberLogin();


$page = 'home';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}


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
