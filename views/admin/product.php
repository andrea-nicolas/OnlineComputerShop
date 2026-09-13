<?php
include "../../controllers/admin_product_control.php";
include "layouts/header.php";
?>

<h1 class="page-title">Products</h1>
<p class="page-sub">Every product in the shop inventory.</p>

<?php
if (!empty($_SESSION["msg"])) {
    echo '<div class="alert-success">' . $_SESSION["msg"] . '</div>';
    unset($_SESSION["msg"]);
}
if (!empty($_SESSION["err"])) {
    echo '<div class="alert-error">' . $_SESSION["err"] . '</div>';
    unset($_SESSION["err"]);
}
?>

<div class="toolbar">
    <a class="btn btn-primary" href="product_form.php">+ Add Product</a>
</div>

<!-- messages written by the AJAX status toggle go here -->
<div id="ajax-message"></div>

<?php if (count($productRows) == 0) { ?>

    <p class="empty-note">No product has been added yet. A category and a brand
    must exist first, then add products under them.</p>

<?php } else { ?>

    <div class="table-wrap">
    <table class="data-table">
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($productRows as $row) {

            // work out how this row's status should be shown, and what
            // clicking the button would change it to
            if ($row["status"] == "inactive") {
                $statusClass = "badge-off";
                $statusLabel = "Inactive";
                $nextStatus = "active";
                $buttonLabel = "Set active";
            } else {
                $statusClass = "badge-ok";
                $statusLabel = "Active";
                $nextStatus = "inactive";
                $buttonLabel = "Set inactive";
            }
        ?>
        <tr>
            <td>
                <?php if ($row["image_path"] != "" && file_exists("../../" . $row["image_path"])) { ?>
                    <img class="table-img" src="../../<?php echo $row["image_path"]; ?>"
                         alt="<?php echo $row["name"]; ?>">
                <?php } else { ?>
                    <span class="no-img">No image</span>
                <?php } ?>
            </td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["category_name"]; ?></td>
            <td><?php echo $row["brand_name"]; ?></td>
            <td><?php echo number_format($row["price"], 2); ?></td>
            <td>
                <?php if ($row["stock"] < 5) { ?>
                    <span class="badge badge-low"><?php echo $row["stock"]; ?></span>
                <?php } else { ?>
                    <span class="badge badge-ok"><?php echo $row["stock"]; ?></span>
                <?php } ?>
            </td>
            <td>
                <span class="badge <?php echo $statusClass; ?>"
                      id="statusbadge-<?php echo $row["id"]; ?>"><?php echo $statusLabel; ?></span>

                <!-- With JavaScript on, onclick returns false so this form never
                     submits and ajax/admin_toggle_status.php does the work instead.
                     With JavaScript off, it is a normal form post. -->
                <form class="inline-form" method="post"
                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="toggle_id" value="<?php echo $row["id"]; ?>">
                    <input type="hidden" name="new_status"
                           id="statusval-<?php echo $row["id"]; ?>"
                           value="<?php echo $nextStatus; ?>">
                    <input type="submit" name="toggle"
                           id="statusbtn-<?php echo $row["id"]; ?>"
                           value="<?php echo $buttonLabel; ?>"
                           class="btn btn-small btn-status"
                           onclick="return toggleStatus(<?php echo $row["id"]; ?>);">
                </form>
            </td>
            <td>
                <a class="btn btn-small" href="product_form.php?id=<?php echo $row["id"]; ?>">Edit</a>

                <form class="inline-form" method="post"
                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                      onsubmit="return confirmDelete('product');">
                    <input type="hidden" name="delete_id" value="<?php echo $row["id"]; ?>">
                    <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-small">
                </form>
            </td>
        </tr>
        <?php } ?>

    </table>
    </div>

<?php } ?>

<?php include "layouts/footer.php"; ?>
