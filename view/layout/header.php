<?php
// The current file name is used to highlight the active menu link.
$currentPage = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Online Computer Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/mycss.css">
</head>
<body>

<div class="topbar">
    <div class="topbar-title">Online Computer Shop <span>Admin</span></div>
    <div class="topbar-user">
        <?php echo $_SESSION["name"]; ?>
        <a class="logout-link" href="../control/logout.php">Logout</a>
    </div>
</div>

<div class="navbar">
    <a class="<?php if ($currentPage == "dashboard.php") echo "active"; ?>" href="dashboard.php">Dashboard</a>
    <a class="<?php if ($currentPage == "category.php" || $currentPage == "category_form.php") echo "active"; ?>" href="category.php">Categories</a>
    <a class="<?php if ($currentPage == "brand.php" || $currentPage == "brand_form.php") echo "active"; ?>" href="brand.php">Brands</a>
    <a class="<?php if ($currentPage == "product.php" || $currentPage == "product_form.php") echo "active"; ?>" href="product.php">Products</a>
</div>

<div class="page">
