<?php
include "../../controllers/admin_category_control.php";
include "layouts/header.php";
?>

<h1 class="page-title">Categories</h1>
<p class="page-sub">Main categories and their sub-categories.</p>

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
    <a class="btn btn-primary" href="category_form.php">+ Add Category</a>
</div>

<?php if (count($categoryRows) == 0) { ?>

    <p class="empty-note">No category has been added yet. Use the button above to add the first one.</p>

<?php } else { ?>

    <div class="table-wrap">
    <table class="data-table">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Parent Category</th>
            <th>Created</th>
            <th>Action</th>
        </tr>

        <?php foreach ($categoryRows as $row) { ?>
        <tr>
            <td>
                <?php if ($row["parent_id"] != NULL) { ?>
                    <span class="tree-mark">&#8627;</span>
                <?php } ?>
                <?php echo $row["name"]; ?>
            </td>
            <td>
                <?php if ($row["parent_id"] == NULL) { ?>
                    <span class="badge badge-ok">Main</span>
                <?php } else { ?>
                    <span class="badge badge-sub">Sub</span>
                <?php } ?>
            </td>
            <td>
                <?php
                if ($row["parent_name"] == NULL) {
                    echo "&mdash;";
                } else {
                    echo $row["parent_name"];
                }
                ?>
            </td>
            <td><?php echo date("d M Y", strtotime($row["created_at"])); ?></td>
            <td>
                <a class="btn btn-small" href="category_form.php?id=<?php echo $row["id"]; ?>">Edit</a>

                <form class="inline-form" method="post"
                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                      onsubmit="return confirmDelete('category');">
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
