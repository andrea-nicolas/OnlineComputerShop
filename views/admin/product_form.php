<?php
include "../../controllers/admin_product_form_control.php";
include "layouts/header.php";
?>

<h1 class="page-title"><?php echo $formTitle; ?></h1>
<p class="page-sub">Choose a category first &mdash; the brand list is then filled with the
brands of that category.</p>

<?php if (count($categoryRows) == 0) { ?>

    <div class="alert-error">
        There is no category yet. <a href="category_form.php">Add a category first.</a>
    </div>

<?php } else { ?>

<div class="form-box">

    <form method="post"
          action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>"
          enctype="multipart/form-data"
          onsubmit="return productvalidation()">

        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
        <span class="error"><?php echo $nameErr; ?></span>
        <span class="error" id="name-error"></span>

        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" onchange="loadBrands()">
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

        <label for="brand_id">Brand</label>
        <select id="brand_id" name="brand_id">
            <?php if ($categoryId == "") { ?>
                <option value="">-- Choose a category first --</option>
            <?php } elseif (count($brandRows) == 0) { ?>
                <option value="">-- This category has no brand yet --</option>
            <?php } else { ?>
                <option value="">-- Choose a brand --</option>
                <?php foreach ($brandRows as $row) { ?>
                    <option value="<?php echo $row["id"]; ?>"
                        <?php if ($brandId == $row["id"]) echo "selected"; ?>>
                        <?php echo $row["name"]; ?>
                    </option>
                <?php } ?>
            <?php } ?>
        </select>
        <span class="error"><?php echo $brandErr; ?></span>
        <span class="error" id="brand-error"></span>
        <span class="hint" id="brand-status"></span>

        <?php if ($categoryId != "" && count($brandRows) == 0) { ?>
            <span class="hint">This category has no brand yet.
                <a href="brand_form.php">Add a brand for it first.</a></span>
        <?php } ?>

        <!-- Fallback for browsers with JavaScript switched off: a normal form
             submit that reloads the brand list from PHP. When JavaScript works,
             myjs.js hides this button and the AJAX call does the same job. -->
        <input type="submit" name="reload" id="reloadbtn" value="Load brands of this category"
               class="btn btn-small reload-btn" onclick="markReload()">

        <label for="price">Price</label>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>">
        <span class="hint">Must be greater than 0. Example: 129.99</span>
        <span class="error"><?php echo $priceErr; ?></span>
        <span class="error" id="price-error"></span>

        <label for="stock">Stock Quantity</label>
        <input type="text" id="stock" name="stock" value="<?php echo $stock; ?>">
        <span class="hint">A whole number. Below 5 shows a low stock alert.</span>
        <span class="error"><?php echo $stockErr; ?></span>
        <span class="error" id="stock-error"></span>

        <label for="manufacturer_review">Manufacturer Review</label>
        <textarea id="manufacturer_review" name="manufacturer_review" rows="3"><?php echo $review; ?></textarea>
        <span class="error"><?php echo $reviewErr; ?></span>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"><?php echo $description; ?></textarea>
        <span class="error"><?php echo $descriptionErr; ?></span>

        <label for="image">Product Image</label>

        <?php if ($currentImage != "" && file_exists("../../" . $currentImage)) { ?>
            <div class="current-image">
                <img class="table-img" src="../../<?php echo $currentImage; ?>" alt="Current image">
                <span class="hint">Current image. Choosing a new file replaces it.</span>
            </div>
        <?php } ?>

        <input type="file" id="image" name="image">
        <span class="hint">JPEG or PNG only, 2MB maximum.</span>
        <span class="error"><?php echo $imageErr; ?></span>
        <span class="error" id="image-error"></span>

        <div class="form-actions">
            <input type="submit" name="save" value="Save" class="btn btn-primary">
            <a class="btn" href="product.php">Cancel</a>
        </div>

    </form>

</div>

<?php } ?>

<?php include "layouts/footer.php"; ?>
