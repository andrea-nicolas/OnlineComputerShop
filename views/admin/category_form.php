<?php
include "../../controllers/admin_category_form_control.php";
include "layouts/header.php";
?>

<h1 class="page-title"><?php echo $formTitle; ?></h1>
<p class="page-sub">Leave the parent empty to create a main category.</p>

<div class="form-box">

    <form method="post"
          action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>"
          onsubmit="return categoryvalidation()">

        <label for="name">Category Name</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
        <span class="error"><?php echo $nameErr; ?></span>
        <span class="error" id="name-error"></span>

        <label for="parent_id">Parent Category</label>
        <select id="parent_id" name="parent_id">
            <option value="">-- None (this is a main category) --</option>
            <?php foreach ($parentRows as $row) { ?>
                <option value="<?php echo $row["id"]; ?>"
                    <?php if ($parentId == $row["id"]) echo "selected"; ?>>
                    <?php echo $row["name"]; ?>
                </option>
            <?php } ?>
        </select>
        <span class="hint">Example: choose "Storage" to create "Portable Storage" under it.</span>
        <span class="error"><?php echo $parentErr; ?></span>

        <div class="form-actions">
            <input type="submit" name="save" value="Save" class="btn btn-primary">
            <a class="btn" href="category.php">Cancel</a>
        </div>

    </form>

</div>

<?php include "layouts/footer.php"; ?>
