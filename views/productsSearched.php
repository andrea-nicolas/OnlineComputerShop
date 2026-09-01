<?php if ($products && $products->num_rows > 0): ?>
    <?php while ($product = $products->fetch_assoc()): ?>
        <div class="product-card">
            <div class="product-image">
                <img src="<?php echo ($product['image_path']); ?>">
            </div>

            <div class="product-details">
                <h3><?php echo ($product['name']); ?></h3>

                <div class="header">
                    <h3><?php echo ($product['price']); ?></h3>
                    <h5>(<?php echo ($product['stock']); ?> in stock)</h5>
                </div>

                <p><?php echo ($product['description']); ?> <br> <br>
                    <i> <?php echo ($product['manufacturer_review']); ?> </i>
                </p>
            </div>

            <div class="footer">
                <p id="qtyErr-<?php echo $product['id']; ?>" style="color:red; font-size:small; font-weight:bold;"></p><br>
                <div class="qty">
                    <input type="number" id="quantity-<?php echo $product['id']; ?>" value="1" class="qty-input" oninput="qtyCheck(<?php echo $product['id']; ?>)">
                </div>
                <div class="add-to-cart">
                    <button type="button" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-products">No products found.</p>
<?php endif; ?>