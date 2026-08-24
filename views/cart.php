<?php 
require "../controllers/cartsController.php";
?>


<!DOCTYPE html>
<head>
    <title>Products</title>
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
                
            </div>

            <div class="cartFooter">
            <a id="products" href="products.php">Continue Browsing</a>
            </div>
        </div>

        <div class="receipt">         
            <div class="order-summary">
                <h1>Order Summary</h1>
                <hr>

                <div class="summary-row">
                    <p>Items</p>
                    <p>xxx</p>
                </div>
                <hr class="inner">

                <div class="summary-row">
                    <p>Sub-Total</p>
                    <p>xxx</p>
                </div>

                <div class="summary-row">
                    <p>Shipping fee</p>
                    <p>xxx</p>
                </div>

                <hr class="inner">

                <div class="summary-row total">
                    <p><b>Total</b></p>
                    <p><b>xxx</b></p>
                </div>

            </div>

            <div class="payment-method">
                <h1>Payment Method</h1><hr>
                <div class="paymentOptions">
                    <button type="radio" name="cash" id="cash" value="cash"><img src="../assets/cash.png" ></button>
                    <button type="radio" name="card" id="card" value="card"><img src="../assets/card.png" ></button>
                    <button type="radio" name="bkash" id="bkash" value="bkash"><img src="../assets/bkash.png" ></button>
                </div>
            </div>

            <div class="checkout">
                <button type="button" name="checkout" id="checkout">Checkout</button>
            </div>
        </div>
    </div>
</body>
</html>