<?php require 'views/layouts/header.php'; ?>

<section class="hero">
    <h1>Build Your Perfect Computer</h1>
    <p>Browse computer components from our online shop.</p>

    <?php if (!isset($_SESSION['user_id'])) { ?>
        <a class="button" href="index.php?page=register">Create Account</a>
    <?php } ?>
</section>

<section class="section-heading">
    <div>
        <h2>Featured Components</h2>
        <p>Latest 6 products from the database.</p>
    </div>

    <!--
        Clicking this button calls loadFeaturedProducts() from app.js.
        AJAX loads the latest 6 products again without reloading the full page.
    -->
    <div>
        <button id="refreshButton"
                class="button secondary"
                type="button"
                onclick="loadFeaturedProducts();">
            Refresh
        </button>
    </div>
</section>

<div id="productList" class="product-grid">

    <?php if (count($products) == 0) { ?>
        <p>No products found.</p>
    <?php } ?>

    <?php foreach ($products as $product) { ?>
        <div class="product-card">
            <div class="product-image">
                <?php if (!empty($product['image_path'])) { ?>
                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php } else { ?>
                    <span>PC</span>
                <?php } ?>
            </div>

            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p>
                <?php echo htmlspecialchars($product['brand_name']); ?> |
                <?php echo htmlspecialchars($product['category_name']); ?>
            </p>
            <strong>৳<?php echo number_format($product['price'], 2); ?></strong>
        </div>
    <?php } ?>

</div>

<?php require 'views/layouts/footer.php'; ?>
