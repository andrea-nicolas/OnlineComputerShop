<?php
require_once __DIR__ . "/../controllers/admin_dashboard_control.php";
$pageTitle = "Admin Dashboard";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>Admin Dashboard</h1>
        <p class="muted">Signed in as <?php echo e($userName); ?>. Latest shop activity at a glance.</p>
    </div>
</div>

<?php if (!$isAdminPage) { ?>

    <div class="alert alert-error"><?php echo e($formError); ?></div>
    <a class="btn btn-light" href="<?php echo link_to('/views/products.php'); ?>">Back to products</a>

<?php } else { ?>

    <div class="stat-row">
        <div class="stat">
            <div class="value"><?php echo (int) $customerCount; ?></div>
            <div class="label">Registered customers</div>
        </div>

        <div class="stat">
            <div class="value"><?php echo (int) $reviewCount; ?></div>
            <div class="label">Reviews written</div>
        </div>

        <div class="stat">
            <div class="value"><?php echo (int) $orderStats["total_orders"]; ?></div>
            <div class="label">Orders placed</div>
        </div>

        <div class="stat">
            <div class="value"><?php echo (int) $orderStats["pending_orders"]; ?></div>
            <div class="label">Orders still pending</div>
        </div>
    </div>

    <div class="card">
        <div class="page-head" style="margin-bottom:12px;">
            <h2 style="margin:0;">Recent Orders</h2>
            <span class="small muted">
                <?php echo money($orderStats["total_value"]); ?> across all orders
            </span>
        </div>

        <?php if (count($recentOrders) === 0) { ?>

            <div class="empty">No orders have been placed yet.</div>

        <?php } else { ?>

            <div class="table-scroll">
                <table class="data">
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="num">Items</th>
                        <th class="num">Total</th>
                        <th></th>
                    </tr>

                    <?php foreach ($recentOrders as $o) { ?>
                        <tr>
                            <td><strong>#<?php echo (int) $o["id"]; ?></strong></td>
                            <td><?php echo e($o["customer_name"]); ?></td>
                            <td class="small" style="white-space:nowrap;"><?php echo show_date($o["order_date"]); ?></td>
                            <td><?php echo e(payment_label($o["payment_method"])); ?></td>
                            <td><span class="status status-<?php echo e($o["status"]); ?>"><?php echo e($o["status"]); ?></span></td>
                            <td class="num"><?php echo (int) $o["item_count"]; ?></td>
                            <td class="num"><?php echo money($o["total_amount"]); ?></td>
                            <td class="num">
                                <a class="btn btn-light btn-small"
                                   href="<?php echo link_to('/views/order_confirmation.php?id=' . (int) $o["id"]); ?>">View</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

        <?php } ?>
    </div>

    <div class="card">
        <div class="page-head" style="margin-bottom:12px;">
            <h2 style="margin:0;">Recent Reviews</h2>
            <a class="small" href="<?php echo link_to('/views/admin_reviews.php'); ?>">Manage all reviews &rarr;</a>
        </div>

        <?php if (count($recentReviews) === 0) { ?>

            <div class="empty">No reviews have been written yet.</div>

        <?php } else { ?>

            <?php foreach ($recentReviews as $r) { ?>
                <div class="review">
                    <div class="review-head">
                        <span class="review-author"><?php echo e($r["reviewer_name"]); ?></span>
                        <span class="small muted">on</span>
                        <a href="<?php echo link_to('/views/product_details.php?id=' . (int) $r["product_id"]); ?>">
                            <?php echo e($r["product_name"]); ?>
                        </a>
                        <span class="review-date"><?php echo show_date($r["created_at"]); ?></span>
                    </div>
                    <p class="review-body"><?php echo e($r["comment"]); ?></p>
                </div>
            <?php } ?>

        <?php } ?>
    </div>

    <p>
        <a class="btn btn-light" href="<?php echo link_to('/views/admin_customers.php'); ?>">Manage customers</a>
        <a class="btn btn-light" href="<?php echo link_to('/views/admin_reviews.php'); ?>">Manage reviews</a>
    </p>

<?php } ?>

<?php include __DIR__ . "/partials/footer.php"; ?>
