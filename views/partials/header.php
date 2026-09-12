<?php

if (!isset($pageTitle)) {
    $pageTitle = "Online Computer Shop";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> &mdash; Online Computer Shop</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <script src="<?php echo BASE_URL; ?>/js/jquery.min.js"></script>
</head>
<body>

<header class="site-header">
    <div class="wrap header-inner">
        <a class="logo" href="<?php echo link_to('/views/products.php'); ?>">
            <span class="logo-mark">PC</span> Online Computer Shop
        </a>

        <nav class="main-nav">
            <a href="<?php echo link_to('/views/products.php'); ?>">Products</a>

            <?php if ($isCustomer) { ?>
                <a href="<?php echo link_to('/views/cart.php'); ?>">Cart</a>
                <a href="<?php echo link_to('/views/my_orders.php'); ?>">My Orders</a>
            <?php } ?>

            <?php if ($isAdmin) { ?>
                <a href="<?php echo link_to('/views/admin_dashboard.php'); ?>">Dashboard</a>
                <a href="<?php echo link_to('/views/admin_customers.php'); ?>">Customers</a>
                <a href="<?php echo link_to('/views/admin_reviews.php'); ?>">Reviews</a>
            <?php } ?>

        </nav>

        <div class="user-box">
            <?php if ($isLoggedIn) { ?>
                <span class="user-name"><?php echo e($userName); ?></span>
                <span class="badge badge-<?php echo e($userRole); ?>"><?php echo e($userRole); ?></span>
                <a class="btn btn-light btn-small" href="<?php echo link_to('/control/logout.php'); ?>">Sign out</a>
            <?php } else { ?>
                <span class="user-name">Guest</span>
                <a class="btn btn-small" href="<?php echo link_to('/views/login.php'); ?>">Sign in</a>
            <?php } ?>
        </div>
    </div>

</header>

<main class="wrap page">
