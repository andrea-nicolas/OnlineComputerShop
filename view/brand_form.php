<?php
include "../control/brand_form_control.php";
include "layout/header.php";
?>

<h1 class="page-title"><?php echo $formTitle; ?></h1>
<p class="page-sub">Example: ASUS, LG and DELL under the Monitor category.</p>

<?php if (count($categoryRows) == 0) { ?>

    <div class="alert-error">
        There is no category yet, so a brand cannot be placed anywhere.
        <a href="category_form.php">Add a category first.</a>
    </div>

<?php } else { ?>

<div class="form-box">

    <form method="post"
          action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>"
          onsubmit="return brandvalidation()">

        <label for="name">Brand Name</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
        <span class="error"><?php echo $nameErr; ?></span>
        <span class="error" id="name-error"></span>

        <label for="category_id">Category</label>
        <select id="category_id" name="category_id">
            <option value="">-- Choose a category --</option>
            <?php foreach ($categoryRows as $row) { ?>
                <option value="<?php echo $row["id"]; ?>"
                    <?php if ($categoryId == $row["id"]) echo "selected"; ?>>
                    <?php
                    if ($row["parent_name"] != NULL) {
                        echo $row["parent_name"] . " > ";
                    }
                    echo $row["name"];
                    ?>
                </option>
            <?php } ?>
        </select>
        <span class="error"><?php echo $categoryErr; ?></span>
        <span class="error" id="category-error"></span>

        <div class="form-actions">
            <input type="submit" name="save" value="Save" class="btn btn-primary">
            <a class="btn" href="brand.php">Cancel</a>
        </div>

    </form>

</div>

<?php } ?>

<?php include "layout/footer.php"; ?>
