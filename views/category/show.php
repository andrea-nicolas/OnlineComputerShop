<?php require 'views/layouts/header.php'; ?>

<section class="section-heading">
    <div>
        <h2><?php echo htmlspecialchars($category['name']); ?></h2>
        <p>Products in this category.</p>
    </div>
</section>

<div class="product-grid">

    <?php if (count($products) == 0) { ?>
        <p>No products are available in this category.</p>
    <?php } ?>

    <?php foreach ($products as $product) { ?>
        <div class="product-card">
            <div class="product-image">
                <?php if ($product['image_path'] != '') { ?>
                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php } else { ?>
                    <span>PC</span>
                <?php } ?>
            </div>

            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['brand_name']); ?></p>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <strong>৳<?php echo number_format($product['price'], 2); ?></strong>
        </div>
    <?php } ?>

</div>

<?php require 'views/layouts/footer.php'; ?>
