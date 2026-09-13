<?php
include "../../controllers/admin_inventory_control.php";
include "layouts/header.php";
?>

<h1 class="page-title">Dashboard</h1>
<p class="page-sub">Summary of everything in the shop inventory.</p>

<div class="card-row">

    <div class="card">
        <div class="card-label">Total Products</div>
        <div class="card-number"><?php echo $totalProducts; ?></div>
        <?php if ($inactiveProducts > 0) { ?>
            <span class="card-note"><?php echo $inactiveProducts; ?> switched off</span><br>
        <?php } ?>
        <a class="card-link" href="product.php">Manage products</a>
    </div>

    <div class="card">
        <div class="card-label">Total Categories</div>
        <div class="card-number"><?php echo $totalCategories; ?></div>
        <a class="card-link" href="category.php">Manage categories</a>
    </div>

    <div class="card">
        <div class="card-label">Total Brands</div>
        <div class="card-number"><?php echo $totalBrands; ?></div>
        <a class="card-link" href="brand.php">Manage brands</a>
    </div>

    <div class="card card-warning">
        <div class="card-label">Low Stock Alerts</div>
        <div class="card-number"><?php echo count($lowStockRows); ?></div>
        <span class="card-note">products with stock below 5</span>
    </div>

</div>

<h2 class="section-title">Low Stock Products</h2>

<?php if (count($lowStockRows) == 0) { ?>

    <p class="empty-note">No product is running low on stock right now.</p>

<?php } else { ?>

    <div class="table-wrap">
    <table class="data-table">
        <tr>
            <th>Product</th>
            <th>Stock Left</th>
        </tr>
        <?php foreach ($lowStockRows as $row) { ?>
        <tr>
            <td><?php echo $row["name"]; ?></td>
            <td><span class="badge badge-low"><?php echo $row["stock"]; ?></span></td>
        </tr>
        <?php } ?>
    </table>
    </div>

<?php } ?>

<?php include "layouts/footer.php"; ?>
