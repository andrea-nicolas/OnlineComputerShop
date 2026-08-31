<?php 
require "../controllers/cartsController.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mozilla+Text:wght@200..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="topnav">
        <a id="logo"><img src="../assets/logo.png"></a>

        <div class="menu-container">  
            <a id="profile"><img src="../assets/profile.png"></a>
        </div>
    </div> 

    <div class="cart">
        <div class="shopping-cart">
            <h1>Cart</h1><hr>

            <div class="cartItems">
                <?php if (empty($cartItems)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo $item['image_path']; ?>" class="cart-item-img">
                            <div>
                                <h4><?php echo $item['name']; ?></h4>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <p>Price: <?php echo number_format($item['price'], 0); ?></p>
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
                    <p><?php echo $totalItems; ?></p>
                </div>
                <hr class="inner">

                <div class="summary-row">
                    <p>Sub-Total</p>
                    <p><?php echo number_format($subtotal, 0); ?></p>
                </div>

                <div class="summary-row">
                    <p>Shipping fee</p>
                    <p><?php echo $subtotal > 0 ? number_format($shippingFee, 0) : "0"; ?></p>
                </div>

                <hr class="inner">

                <div class="summary-row total">
                    <p><b>Total</b></p>
                    <p><b><?php echo number_format($totalPrice, 0); ?></b></p>
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
</body>
</html>
