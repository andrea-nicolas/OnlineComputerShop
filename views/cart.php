<?php 
require "../controllers/productsController.php"; 
require "../controllers/cartsController.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/products.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mozilla+Text:wght@200..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="topnav">
        <a id="logo"><img src="../assets/logo.png"></a>

        <div class="search-container">  
            <input type="text" placeholder="Search..">
            <button id="search-button"><img src="../assets/search.png"></button>
        </div>

        <div class="menu-container">  
            <a id="shopping-cart" href="cart.php"><img src="../assets/shopping-cart.png"></a>
            <a id="profile"><img src="../assets/profile.png"></a>
        </div>
    </div> 

    <section class="hero">
        <h1>Browse Components</h1>
        <h4><?php echo $pageTitle; ?></h4>
    </section>

    <div class="filter-bar">
        <div>
            <b>Categories: </b>
            <a href="products.php">All</a>
            <?php if ($categories): ?>
                <?php foreach ($categories as $cat): ?>
                    <a href="products.php?category=<?php echo $cat['name']; ?>">
                        <?php echo $cat['name']; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div>
            <b>Brands: </b>
            <?php if ($brands): ?>
                <?php foreach ($brands as $b): ?>
                    <a href="products.php?brand=<?php echo $b['name']; ?>">
                        <?php echo $b['name']; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <section class="product-container">
    <?php if ($products && $products->num_rows > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo $product['image_path']; ?>">
                </div>

                <div class="product-details">
                    <h3><?php echo $product['name']; ?></h3>

                    <div class="header">
                        <h3><?php echo number_format($product['price'], 0); ?></h3>
                        <h5>(<?php echo $product['stock']; ?> in stock)</h5>
                    </div>

                    <p><?php echo $product['description']; ?> <br> <br> 
                        <i> <?php echo $product['manufacturer_review']; ?> </i> 
                    </p>
                </div>

                <form method="POST">
                    <div class="footer">
                        <p id="qtyErr-<?php echo $product['id']; ?>"> <?php echo $qtyErr[$product['id']] ?? ""; ?> </p><br>
                        <div class="qty">
                            <input type="number" id="quantity-<?php echo $product['id']; ?>" name="quantity" value="0" class="qty-input" oninput="qtyCheck(<?php echo $product['id']; ?>)">
                        </div>
                        <div class="add-to-cart">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="addtocart">Add to Cart</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-products">No products found for this filter.</p>
    <?php endif; ?>
    </section>
</body>
</html>
