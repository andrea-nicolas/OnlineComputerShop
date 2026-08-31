<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Online Computer Shop</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>

<header class="main-header">
    <div class="container nav-row">
        <!-- Clicking the logo opens index.php, so HomeController runs. -->
        <a class="logo" href="index.php">Online Computer Shop</a>

        <nav class="main-nav">
            <a href="index.php">Home</a>

            <?php if (isset($_SESSION['user_id'])) { ?>

                <!-- Clicking Profile sends page=profile to index.php. -->
                <a href="index.php?page=profile">Profile</a>

                <span class="welcome">
                    Hi, <?php echo htmlspecialchars($_SESSION['name']); ?>
                    (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                </span>

                <!-- Clicking Logout sends page=logout to index.php. -->
                <a href="index.php?page=logout">Logout</a>

            <?php } else { ?>

                <a href="index.php?page=register">Register</a>
                <a href="index.php?page=login">Login</a>

            <?php } ?>
        </nav>
    </div>
</header>

<!-- Category bar comes from the categories table. -->
<div class="category-bar">
    <div class="container category-links">

        <?php if (count($categories) > 0) { ?>

            <?php foreach ($categories as $categoryItem) { ?>
                <a href="index.php?page=category&id=<?php echo $categoryItem['id']; ?>">
                    <?php echo htmlspecialchars($categoryItem['name']); ?>
                </a>
            <?php } ?>

        <?php } else { ?>
            <span>No categories found.</span>
        <?php } ?>

    </div>
</div>

<main class="container main-content">

    <!-- Show one-time success/info message from the controller. -->
    <?php if (isset($_SESSION['message'])) { ?>
        <div class="success-message">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>

        <?php unset($_SESSION['message']); ?>
    <?php } ?>
