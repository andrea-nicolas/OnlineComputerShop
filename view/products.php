<?php
require_once __DIR__ . "/../control/products_control.php";
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
                <a class="name" href="<?php echo link_to('/view/product_details.php?id=' . (int) $p["id"]); ?>">
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
