<?php
require_once __DIR__ . "/../control/order_confirmation_control.php";
$pageTitle = ($order !== null) ? ("Order #" . (int) $order["id"]) : "Order";
include __DIR__ . "/partials/header.php";
?>

<?php if ($order === null) { ?>

    <div class="card">
        <h1>Order not available</h1>
        <p class="muted"><?php echo e($accessError); ?></p>
        <a class="btn" href="<?php echo link_to('/view/products.php'); ?>">Back to products</a>
    </div>

<?php } else { ?>

    <?php if ($justPlaced) { ?>
        <div class="alert alert-success">
            <strong>Thank you, your order has been placed.</strong>
            Your cart is now empty and the order is waiting to be processed.
        </div>
    <?php } ?>

    <div class="page-head">
        <div>
            <h1>Order #<?php echo (int) $order["id"]; ?></h1>
            <p class="muted">Placed on <?php echo show_date($order["order_date"]); ?></p>
        </div>
        <div>
            <span class="status status-<?php echo e($order["status"]); ?>"><?php echo e($order["status"]); ?></span>
        </div>
    </div>

    <div class="card">
        <h2>Summary</h2>

        <ul class="spec-list">
            <li><span>Order ID</span><span>#<?php echo (int) $order["id"]; ?></span></li>
            <li><span>Customer</span><span><?php echo e($order["customer_name"]); ?></span></li>
            <li><span>Payment method</span><span><?php echo e(payment_label($order["payment_method"])); ?></span></li>
            <li><span>Status</span><span><?php echo e($order["status"]); ?></span></li>
            <li><span>Order date</span><span><?php echo show_date($order["order_date"]); ?></span></li>
        </ul>
    </div>

    <div class="card">
        <h2>Items (<?php echo count($orderItems); ?>)</h2>

        <div class="table-scroll">
            <table class="data">
                <tr>
                    <th>Product</th>
                    <th class="num">Unit price</th>
                    <th class="num">Quantity</th>
                    <th class="num">Subtotal</th>
                </tr>

                <?php foreach ($orderItems as $item) { ?>
                    <tr>
                        <td>
                            <a href="<?php echo link_to('/view/product_details.php?id=' . (int) $item["product_id"]); ?>">
                                <?php echo e($item["product_name"]); ?>
                            </a>
                        </td>
                        <td class="num"><?php echo money($item["unit_price"]); ?></td>
                        <td class="num"><?php echo (int) $item["quantity"]; ?></td>
                        <td class="num"><?php echo money((float) $item["unit_price"] * (int) $item["quantity"]); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="total-row">
            <span><strong>Total paid</strong></span>
            <span class="total-value"><?php echo money($order["total_amount"]); ?></span>
        </div>
    </div>

    <p>
        <a class="btn btn-light" href="<?php echo link_to('/view/products.php'); ?>">Keep shopping</a>
        <?php if ($isCustomer) { ?>
            <a class="btn btn-light" href="<?php echo link_to('/view/my_orders.php'); ?>">My orders</a>
        <?php } ?>
    </p>

<?php } ?>

<?php include __DIR__ . "/partials/footer.php"; ?>
