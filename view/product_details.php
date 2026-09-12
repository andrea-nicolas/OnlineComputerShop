<?php
require_once __DIR__ . "/../control/product_control.php";
$pageTitle = ($product !== null) ? $product["name"] : "Product not found";
include __DIR__ . "/partials/header.php";
?>

<?php if ($product === null) { ?>

    <div class="card">
        <h1>Product not found</h1>
        <p class="muted">That product does not exist, or the link is missing an id.</p>
        <a class="btn" href="<?php echo link_to('/view/products.php'); ?>">Back to products</a>
    </div>

<?php } else { ?>

    <p class="small">
        <a href="<?php echo link_to('/view/products.php'); ?>">&larr; All products</a>
    </p>

    <div class="card">
        <div class="detail">
            <div>
                <img src="<?php echo $product["image_path"] ? BASE_URL . "/" . e($product["image_path"]) : BASE_URL . "/images/placeholder.svg"; ?>"
                     alt="<?php echo e($product["name"]); ?>">
            </div>

            <div>
                <p class="small muted" style="margin:0;">
                    <?php echo e($product["category_name"]); ?> &middot; <?php echo e($product["brand_name"]); ?>
                </p>

                <h1><?php echo e($product["name"]); ?></h1>

                <p style="font-size:22px;font-weight:700;color:var(--brand-dark);margin:0 0 10px;">
                    <?php echo money($product["price"]); ?>
                </p>

                <p>
                    <?php if ((int) $product["stock"] > 0) { ?>
                        <span class="in-stock">In stock</span>
                        <span class="muted small">(<?php echo (int) $product["stock"]; ?> available)</span>
                    <?php } else { ?>
                        <span class="no-stock">Out of stock</span>
                    <?php } ?>
                </p>

                <h3>Description</h3>
                <p><?php echo e($product["description"]); ?></p>

                <h3>Manufacturer review</h3>
                <p class="muted"><?php echo e($product["manufacturer_review"]); ?></p>

                <ul class="spec-list">
                    <li><span>Category</span><span><?php echo e($product["category_name"]); ?></span></li>
                    <li><span>Brand</span><span><?php echo e($product["brand_name"]); ?></span></li>
                    <li><span>Listed</span><span><?php echo show_date($product["created_at"]); ?></span></li>
                    <li><span>Reviews</span><span><?php echo count($reviews); ?></span></li>
                </ul>

                <?php if ($cartMsg !== "") { ?>
                    <div class="alert alert-success" style="margin-top:14px;">
                        <?php echo e($cartMsg); ?>
                        <a href="<?php echo link_to('/view/cart.php'); ?>">Go to cart &rarr;</a>
                    </div>
                <?php } ?>

                <?php if ($isCustomer && (int) $product["stock"] > 0) { ?>
                    <form action="" method="post" style="margin-top:14px;">
                <?php echo csrf_field(); ?>
                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    </form>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="card" id="reviews" data-product="<?php echo (int) $product["id"]; ?>">
        <h2>Customer Reviews (<span id="review-count"><?php echo count($reviews); ?></span>)</h2>

        <?php if ($successMsg !== "") { ?>
            <div class="alert alert-success"><?php echo e($successMsg); ?></div>
        <?php } ?>

        <?php if ($formError !== "") { ?>
            <div class="alert alert-error"><?php echo e($formError); ?></div>
        <?php } ?>

        <div id="review-message" style="display:none;"></div>

        <?php if ($isCustomer) { ?>

            <form action="" method="post" id="review-form">
                <?php echo csrf_field(); ?>
                <div class="field">
                    <label for="reviewer_name">Posting as</label>
                    <input type="text" id="reviewer_name" value="<?php echo e($userName); ?>" readonly>
                    <span class="small muted">Your name comes from your profile.</span>
                </div>

                <div class="field">
                    <label for="comment">
                        Your review
                        <span class="char-count" id="char-count">0 / 500</span>
                    </label>
                    <textarea id="comment" name="comment"
                              placeholder="How did this component work for you?"><?php echo e($commentValue); ?></textarea>
                    <span class="error-text" id="comment-error"><?php echo e($commentError); ?></span>
                </div>

                <button type="submit" name="post_review" class="btn">Post review</button>
            </form>

        <?php } elseif ($isAdmin) { ?>

            <div class="alert alert-info">
                You are signed in as an admin. Only customers post reviews. Admins remove
                them from the review management page.
            </div>

        <?php } else { ?>

            <div class="alert alert-warn">
                Only logged in customers can post a review. Visitors can read them.
            </div>

        <?php } ?>

        <hr style="border:none;border-top:1px solid var(--line);margin:22px 0;">

        <div id="review-list">
        <?php if (count($reviews) === 0) { ?>

            <div class="empty">No reviews yet. Be the first to write one.</div>

        <?php } else { ?>

            <?php foreach ($reviews as $r) { ?>
                <?php $isMine = ($isLoggedIn && (int) $r["user_id"] === $userId); ?>

                <div class="review <?php echo $isMine ? "mine" : ""; ?>">
                    <div class="review-head">
                        <span class="review-author"><?php echo e($r["reviewer_name"]); ?></span>
                        <?php if ($isMine) { ?>
                            <span class="badge badge-customer">you</span>
                        <?php } ?>
                        <span class="review-date"><?php echo show_date($r["created_at"]); ?></span>

                        <?php if ($isMine) { ?>
                            <form action="" method="post" style="margin-left:auto;"
                                  onsubmit="return confirmDeleteReview()">
                <?php echo csrf_field(); ?>
                                <input type="hidden" name="review_id" value="<?php echo (int) $r["id"]; ?>">
                                <button type="submit" name="delete_review" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        <?php } ?>
                    </div>

                    <p class="review-body"><?php echo e($r["comment"]); ?></p>
                </div>
            <?php } ?>

        <?php } ?>
        </div>
    </div>

<?php } ?>

<script src="<?php echo BASE_URL; ?>/js/review_ajax.js"></script>

<?php include __DIR__ . "/partials/footer.php"; ?>
