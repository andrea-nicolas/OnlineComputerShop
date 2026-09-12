<?php
require_once __DIR__ . "/../control/admin_customers_control.php";
$pageTitle = "Manage Customers";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>Customers</h1>
        <p class="muted">Every registered customer account. Removing one also removes their reviews, cart and orders.</p>
    </div>
</div>

<?php if (!$isAdminPage) { ?>

    <div class="alert alert-error"><?php echo e($formError); ?></div>
    <a class="btn btn-light" href="<?php echo link_to('/view/products.php'); ?>">Back to products</a>

<?php } else { ?>

    <?php if ($successMsg !== "") { ?>
        <div class="alert alert-success"><?php echo e($successMsg); ?></div>
    <?php } ?>

    <?php if ($formError !== "") { ?>
        <div class="alert alert-error"><?php echo e($formError); ?></div>
    <?php } ?>

    <?php if (count($customers) === 0) { ?>

        <div class="empty">There are no customer accounts yet.</div>

    <?php } else { ?>

        <div class="card">
            <h2>All customers (<?php echo count($customers); ?>)</h2>

            <div class="table-scroll">
                <table class="data">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th class="num">Reviews</th>
                        <th class="num">Orders</th>
                        <th></th>
                    </tr>

                    <?php foreach ($customers as $c) { ?>
                        <tr>
                            <td><?php echo (int) $c["id"]; ?></td>
                            <td><strong><?php echo e($c["name"]); ?></strong></td>
                            <td><?php echo e($c["email"]); ?></td>
                            <td class="small"><?php echo show_date($c["created_at"]); ?></td>
                            <td class="num"><?php echo (int) $c["review_count"]; ?></td>
                            <td class="num"><?php echo (int) $c["order_count"]; ?></td>
                            <td class="num">
                                <form action="" method="post"
                                      onsubmit="return confirmDeleteCustomer('<?php echo e(addslashes($c["name"])); ?>', <?php echo (int) $c["review_count"]; ?>, <?php echo (int) $c["order_count"]; ?>)">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="customer_id" value="<?php echo (int) $c["id"]; ?>">
                                    <button type="submit" name="delete_customer" class="btn btn-danger btn-small">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <p class="small muted" style="margin-top:12px;">
                Admin accounts are not listed here and cannot be deleted from this page.
            </p>
        </div>

    <?php } ?>

<?php } ?>

<script src="<?php echo BASE_URL; ?>/js/admin_validation.js"></script>

<?php include __DIR__ . "/partials/footer.php"; ?>
