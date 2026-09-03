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
        
        <a class="logo" href="index.php">Online Computer Shop</a>

        <nav class="main-nav">
            <a href="index.php">Home</a>

            <?php if (isset($_SESSION['user_id'])) { ?>

                
                <a href="index.php?page=profile">Profile</a>

                <span class="welcome">
                    Hi, <?php echo htmlspecialchars($_SESSION['name']); ?>
                    (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                </span>

               
                <a href="index.php?page=logout">Logout</a>

            <?php } else { ?>

                <a href="index.php?page=register">Register</a>
                <a href="index.php?page=login">Login</a>

            <?php } ?>
        </nav>
    </div>
</header>


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

    
    <?php if (isset($_SESSION['message'])) { ?>
        <div class="success-message">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>

        <?php unset($_SESSION['message']); ?>
    <?php } ?>
