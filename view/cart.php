<?php
require_once __DIR__ . "/../control/cart_control.php";
$pageTitle = "Cart";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>Your Cart</h1>
        <p class="muted">Check the items, choose how you want to pay, then place the order.</p>
    </div>
</div>

<?php if (!$canCheckout) { ?>

    <div class="alert alert-warn">
        <?php if ($isAdmin) { ?>
            Admin accounts do not have a cart. Sign in as a customer to use the checkout.
        <?php } else { ?>
            You have to log in as a customer before you can use the cart.
        <?php } ?>
    </div>

    <a class="btn btn-light" href="<?php echo link_to('/view/products.php'); ?>">Browse products</a>

<?php } else { ?>

    <?php if ($successMsg !== "") { ?>
        <div class="alert alert-success"><?php echo e($successMsg); ?></div>
    <?php } ?>

    <?php if ($formError !== "") { ?>
        <div class="alert alert-error"><?php echo e($formError); ?></div>
    <?php } ?>

    <div id="cart-message" style="display:none;"></div>

    <?php if (count($cartItems) === 0) { ?>

        <div class="empty">
            <p>Your cart is empty.</p>
            <a class="btn" href="<?php echo link_to('/view/products.php'); ?>">Browse products</a>
        </div>

    <?php } else { ?>

        <div class="card">
            <h2>Items (<span id="cart-count"><?php echo count($cartItems); ?></span>)</h2>

            <div class="table-scroll">
                <table class="data">
                    <tr>
                        <th>Product</th>
                        <th class="num">Unit price</th>
                        <th>Quantity</th>
                        <th class="num">Subtotal</th>
                        <th></th>
                    </tr>

                    <?php foreach ($cartItems as $item) { ?>
                        <tr id="cart-row-<?php echo (int) $item["cart_id"]; ?>">
                            <td>
                                <a href="<?php echo link_to('/view/product_details.php?id=' . (int) $item["product_id"]); ?>">
                                    <?php echo e($item["name"]); ?>
                                </a>
                                <div class="small muted"><?php echo e($item["brand_name"]); ?></div>
                                <?php if ((int) $item["quantity"] > (int) $item["stock"]) { ?>
                                    <div class="small" style="color:var(--bad);">
                                        Only <?php echo (int) $item["stock"]; ?> left in stock
                                    </div>
                                <?php } ?>
                            </td>
                            <td class="num"><?php echo money($item["price"]); ?></td>

                            <td>
                                <form action="" method="post" class="qty-form"
                                      onsubmit="return cartUpdate(this)">
                <?php echo csrf_field(); ?>
                                    <input type="hidden" name="cart_id" value="<?php echo (int) $item["cart_id"]; ?>">
                                    <input type="number" name="quantity" class="qty-input"
                                           value="<?php echo (int) $item["quantity"]; ?>"
                                           min="1" max="<?php echo (int) $item["stock"]; ?>">
                                    <button type="submit" name="update_qty" class="btn btn-light btn-small">Update</button>
                                </form>
                            </td>

                            <td class="num" id="line-total-<?php echo (int) $item["cart_id"]; ?>"><?php echo money((float) $item["price"] * (int) $item["quantity"]); ?></td>

                            <td class="num">
                                <form action="" method="post"
                                      onsubmit="return cartRemove(this, '<?php echo e(addslashes($item["name"])); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="cart_id" value="<?php echo (int) $item["cart_id"]; ?>">
                                    <button type="submit" name="remove_item" class="btn btn-danger btn-small">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="total-row">
                <span><strong>Order total</strong></span>
                <span class="total-value cart-total-value"><?php echo money($cartTotal); ?></span>
            </div>
        </div>

        <div class="card">
            <h2>Payment Method</h2>

            <form action="" method="post" onsubmit="return validateCheckoutForm()">
                <?php echo csrf_field(); ?>

                <div class="pay-options">
                    <?php foreach (payment_methods() as $value => $label) { ?>
                        <div class="pay-option" id="pay-card-<?php echo e($value); ?>">
                            <input type="radio"
                                   id="pay-<?php echo e($value); ?>"
                                   name="payment_method"
                                   value="<?php echo e($value); ?>"
                                   <?php echo ($paymentMethod === $value) ? "checked" : ""; ?>>
                            <label for="pay-<?php echo e($value); ?>">
                                <span class="pay-name"><?php echo e($label); ?></span>
                                <span class="pay-note">
                                    <?php
                                    if ($value === "cash") {
                                        echo "Pay the rider when the parcel arrives.";
                                    } elseif ($value === "card") {
                                        echo "Visa, Mastercard or any local debit card.";
                                    } else {
                                        echo "Send the amount from your bKash wallet.";
                                    }
                                    ?>
                                </span>
                            </label>
                        </div>
                    <?php } ?>
                </div>

                <span class="error-text" id="payment_method-error"><?php echo e($paymentError); ?></span>

                <div class="total-row">
                    <span>
                        <strong><?php echo count($cartItems); ?></strong> item<?php echo (count($cartItems) === 1 ? "" : "s"); ?>
                        &middot; total to pay
                    </span>
                    <span class="total-value cart-total-value"><?php echo money($cartTotal); ?></span>
                </div>

                <p style="margin-top:16px;">
                    <button type="submit" name="place_order" class="btn">Place order</button>
                    <a class="btn btn-light" href="<?php echo link_to('/view/products.php'); ?>">Keep shopping</a>
                </p>
            </form>
        </div>

    <?php } ?>

<?php } ?>

<script src="<?php echo BASE_URL; ?>/js/cart_ajax.js"></script>
<script src="<?php echo BASE_URL; ?>/js/checkout_validation.js"></script>

<?php include __DIR__ . "/partials/footer.php"; ?>
