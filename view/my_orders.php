<?php
require_once __DIR__ . "/../control/my_orders_control.php";
$pageTitle = "My Orders";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>My Orders</h1>
        <p class="muted">Every order you have placed, newest first.</p>
    </div>
</div>

<?php if ($formError !== "") { ?>

    <div class="alert alert-warn"><?php echo e($formError); ?></div>
    <a class="btn btn-light" href="<?php echo link_to('/view/products.php'); ?>">Browse products</a>

<?php } elseif (count($orders) === 0) { ?>

    <div class="empty">
        <p>You have not placed any order yet.</p>
        <a class="btn" href="<?php echo link_to('/view/products.php'); ?>">Browse products</a>
    </div>

<?php } else { ?>

    <div class="card">
        <div class="table-scroll">
            <table class="data">
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="num">Items</th>
                    <th class="num">Total</th>
                    <th></th>
                </tr>

                <?php foreach ($orders as $o) { ?>
                    <tr>
                        <td><strong>#<?php echo (int) $o["id"]; ?></strong></td>
                        <td><?php echo show_date($o["order_date"]); ?></td>
                        <td><?php echo e(payment_label($o["payment_method"])); ?></td>
                        <td><span class="status status-<?php echo e($o["status"]); ?>"><?php echo e($o["status"]); ?></span></td>
                        <td class="num"><?php echo (int) $o["item_count"]; ?></td>
                        <td class="num"><?php echo money($o["total_amount"]); ?></td>
                        <td class="num">
                            <a class="btn btn-light btn-small"
                               href="<?php echo link_to('/view/order_confirmation.php?id=' . (int) $o["id"]); ?>">View</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

<?php } ?>

<?php include __DIR__ . "/partials/footer.php"; ?>
