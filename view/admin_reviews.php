<?php
require_once __DIR__ . "/../control/admin_reviews_control.php";
$pageTitle = "Manage Reviews";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>Reviews</h1>
        <p class="muted">Every review customers have written. An admin can remove any of them.</p>
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

    <?php if (count($reviews) === 0) { ?>

        <div class="empty">No reviews have been written yet.</div>

    <?php } else { ?>

        <div class="card">
            <h2>All reviews (<span id="admin-review-count"><?php echo count($reviews); ?></span>)</h2>

            <div id="admin-review-message" style="display:none;"></div>

            <div class="table-scroll" id="admin-review-list">
                <table class="data">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Reviewer</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th></th>
                    </tr>

                    <?php foreach ($reviews as $r) { ?>
                        <tr>
                            <td><?php echo (int) $r["id"]; ?></td>
                            <td>
                                <a href="<?php echo link_to('/view/product_details.php?id=' . (int) $r["product_id"]); ?>">
                                    <?php echo e($r["product_name"]); ?>
                                </a>
                            </td>
                            <td>
                                <strong><?php echo e($r["reviewer_name"]); ?></strong>
                                <div class="small muted"><?php echo e($r["reviewer_email"]); ?></div>
                            </td>
                            <td style="max-width:380px;"><?php echo e($r["comment"]); ?></td>
                            <td class="small" style="white-space:nowrap;"><?php echo show_date($r["created_at"]); ?></td>
                            <td class="num">
                                <form action="" method="post" class="admin-delete-review"
                                      data-author="<?php echo e($r["reviewer_name"]); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="review_id" value="<?php echo (int) $r["id"]; ?>">
                                    <button type="submit" name="delete_review" class="btn btn-danger btn-small">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

    <?php } ?>

<?php } ?>

<script src="<?php echo BASE_URL; ?>/js/admin_validation.js"></script>

<?php include __DIR__ . "/partials/footer.php"; ?>
