<?php 
require_once "../controllers/cartsController.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mozilla+Text:wght@200..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="topnav">
        <a id="logo" href="products.php"><img src="../assets/logo.png"></a>

        <form action="products.php" method="GET" class="search-container">  
            <input type="text" name="q" placeholder="Search..">
            <button type="submit" id="search-button"><img src="../assets/search.png"></button>
        </form>

        <div class="menu-container">  
            <a id="profile"><img src="../assets/profile.png"></a>
        </div>
    </div> 

    <div class="cart">
        <div class="shopping-cart">
            <h1>Cart</h1><hr>

            <div class="cartItems" id="cart-items-container">
                <?php if (empty($cartItems)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item" id="cart-item-<?php echo $item['product_id']; ?>">
                            <img src="<?php echo $item['image_path']; ?>" class="cart-item-img">
                            <div>
                                <h4><?php echo $item['name']; ?></h4>
                                <p>Price: <span id="unit-price-<?php echo $item['product_id']; ?>"><?php echo $item['price']; ?></span></p>
                                <p>
                                    Quantity: 
                                    <button type="button" onclick="updateQuantity(<?php echo $item['product_id']; ?>, -1)">-</button>
                                    <span id="qty-<?php echo $item['product_id']; ?>"><?php echo $item['quantity']; ?></span>
                                    <button type="button" onclick="updateQuantity(<?php echo $item['product_id']; ?>, 1)">+</button>
                                </p>
                                <p>Subtotal: <span id="subtotal-<?php echo $item['product_id']; ?>"><?php echo $item['subtotal']; ?></span></p>
                                <button type="button" onclick="removeItem(<?php echo $item['product_id']; ?>)">Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="cartFooter">
                <a id="products" href="products.php">Continue Browsing</a>
            </div>
        </div>

        <form method="POST" class="receipt">         
            <div class="order-summary">
                <h1>Order Summary</h1>
                <hr>

                <div class="summary-row">
                    <p>Items</p>
                    <p id="summary-items"><?php echo $totalItems; ?></p>
                </div>
                <hr class="inner">

                <div class="summary-row">
                    <p>Sub-Total</p>
                    <p id="summary-subtotal"><?php echo $subtotal; ?></p>
                </div>

                <div class="summary-row">
                    <p>Shipping fee</p>
                    <p id="summary-shipping"><?php echo $displayShipping; ?></p>
                </div>

                <hr class="inner">

                <div class="summary-row total">
                    <p><b>Total</b></p>
                    <p id="summary-total"><b><?php echo $totalPrice; ?></b></p>
                </div>
            </div>

            <div class="payment-method">
                <h1>Payment Method</h1><hr>
                <div class="paymentOptions">
                    <label class="payment-label">
                        <input type="radio" name="payment_method" value="cash">
                        <img src="../assets/cash.png" alt="Cash" class="payment-img">
                    </label>
                    <label class="payment-label">
                        <input type="radio" name="payment_method" value="card">
                        <img src="../assets/card.png" alt="Card" class="payment-img">
                    </label>
                    <label class="payment-label">
                        <input type="radio" name="payment_method" value="bkash">
                        <img src="../assets/bkash.png" alt="bKash" class="payment-img">
                    </label>
                </div>

                <?php if (!empty($orderMsg)): ?>
                    <p class="order-alert">
                        <?php echo $orderMsg; ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="checkout">
                <button type="submit" name="checkout" id="checkout">Checkout</button>
            </div>
        </form>
    </div>

    <script src="../assets/js/products.js"></script>
</body>
</html>