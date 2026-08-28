<?php
include "../control/brand_control.php";
include "layout/header.php";
?>

<h1 class="page-title">Brands</h1>
<p class="page-sub">Every brand belongs to one category.</p>

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
    <a class="btn btn-primary" href="brand_form.php">+ Add Brand</a>
</div>

<?php if (count($brandRows) == 0) { ?>

    <p class="empty-note">No brand has been added yet. A category must exist first,
    then add brands under it.</p>

<?php } else { ?>

    <div class="table-wrap">
    <table class="data-table">
        <tr>
            <th>Brand</th>
            <th>Category</th>
            <th>Created</th>
            <th>Action</th>
        </tr>

        <?php foreach ($brandRows as $row) { ?>
        <tr>
            <td><?php echo $row["name"]; ?></td>
            <td>
                <?php
                if ($row["parent_name"] != NULL) {
                    echo $row["parent_name"] . " &rsaquo; ";
                }
                echo $row["category_name"];
                ?>
            </td>
            <td><?php echo date("d M Y", strtotime($row["created_at"])); ?></td>
            <td>
                <a class="btn btn-small" href="brand_form.php?id=<?php echo $row["id"]; ?>">Edit</a>

                <form class="inline-form" method="post"
                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                      onsubmit="return confirmDelete('brand');">
                    <input type="hidden" name="delete_id" value="<?php echo $row["id"]; ?>">
                    <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-small">
                </form>
            </td>
        </tr>
        <?php } ?>

    </table>
    </div>

<?php } ?>

<?php include "layout/footer.php"; ?>
