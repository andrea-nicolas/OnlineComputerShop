<?php
require_once __DIR__ . "/../controllers/products_control.php";
$pageTitle = "Products";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>All Products</h1>
        <p class="muted">Open a product to read its reviews and write your own.</p>
    </div>
</div>

<?php if (count($products) === 0) { ?>

    <div class="empty">
        No products yet. Import <code>sql/seed_data.sql</code> to load demo products.
    </div>

<?php } else { ?>

    <div class="product-grid">
        <?php foreach ($products as $p) { ?>
            <div class="product-card">
                <img class="thumb" loading="lazy"
                     src="<?php echo $p["image_path"] ? BASE_URL . "/" . e($p["image_path"]) : BASE_URL . "/images/placeholder.svg"; ?>"
                     alt="<?php echo e($p["name"]); ?>">

                <span class="small muted"><?php echo e($p["category_name"]); ?> &middot; <?php echo e($p["brand_name"]); ?></span>
                <a class="name" href="<?php echo link_to('/views/product_details.php?id=' . (int) $p["id"]); ?>">
                    <?php echo e($p["name"]); ?>
                </a>
                <p class="small muted" style="margin:0;"><?php echo e($p["manufacturer_review"]); ?></p>
                <span class="price"><?php echo money($p["price"]); ?></span>
                <span class="small muted">
                    <?php echo (int) $p["review_count"]; ?> review<?php echo ((int) $p["review_count"] === 1 ? "" : "s"); ?>
                    &middot;
                    <?php if ((int) $p["stock"] > 0) { ?>
                        <span class="in-stock"><?php echo (int) $p["stock"]; ?> in stock</span>
                    <?php } else { ?>
                        <span class="no-stock">out of stock</span>
                    <?php } ?>
                </span>
            </div>
        <?php } ?>
    </div>

<?php } ?>

<?php include __DIR__ . "/partials/footer.php"; ?>
<?php 
require_once "../controllers/productsController.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mozilla+Text:wght@200..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="topnav">
        <a id="logo" href="products.php"><img src="../assets/logo.png"></a>

        <div class="search-container">  
            <input type="text" id="search-input" value="<?php echo ($searchQuery); ?>" placeholder="Search.." onkeyup="filterProducts()">
            <button id="search-button" type="button" onclick="filterProducts()"><img src="../assets/search.png"></button>
        </div>

        <div class="menu-container">  
            <a id="shopping-cart" href="cart.php">
                <img src="../assets/shopping-cart.png">
                <span id="cart-count"><?php echo $cartCount; ?></span>
            </a>
            <a id="profile"><img src="../assets/profile.png"></a>
        </div>
    </div> 

    <section class="hero">
        <h1>Browse Components</h1>
        <h4><?php echo $pageTitle; ?></h4>
    </section>

    <div class="filter-bar">
        <div>
            <b>Category: </b>
            <select id="filter-category" onchange="filterProducts()">
                <option value="">All</option>
                <?php if ($categories): ?>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['name']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['name']) ? 'selected' : ''; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
        <div>
            <b>Brand: </b>
            <select id="filter-brand" onchange="filterProducts()">
                <option value="">All</option>
                <?php if ($brands): ?>
                    <?php while ($b = $brands->fetch_assoc()): ?>
                        <option value="<?php echo $b['name']; ?>" <?php echo (isset($_GET['brand']) && $_GET['brand'] == $b['name']) ? 'selected' : ''; ?>>
                            <?php echo $b['name']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
        <div>
            <b>Max Price: </b>
            <input type="range" id="filter-price-range" min="0" max="200000" step="1000" value="200000" oninput="updatePriceLabel(this.value); filterProducts();">
            <span id="price-range-val">200000</span>
        </div>
    </div>
    
    <section class="product-container" id="product-grid">
        <?php include "productsSearched.php"; ?>
    </section>

    <script src="../assets/js/products.js"></script>
</body>
</html>
