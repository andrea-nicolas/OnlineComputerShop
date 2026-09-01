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