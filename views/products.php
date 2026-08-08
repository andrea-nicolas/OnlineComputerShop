<!DOCTYPE html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/products.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mozilla+Text:wght@200..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="topnav">

        <a id="logo"><img src="../assets/logo.png"></a>

        <div class="search-container">  
            <input type="text" placeholder="Search..">
            <button id="search-button"><img src="../assets/search.png" ></button>
        </div>

        <div class="menu-container">  
            <a id="shopping-cart" ><img src="../assets/shopping-cart.png"></a>
            <a id="profile"><img src="../assets/profile.png"></a>
        </div>

    </div> 

    <section class="hero">
        <h1>Browse Components<h1>
        <h4> all products</h4>
    </section>
    
    <section class="product-container">
        <div div class="product-card">
            <div class="product-image"> <img src="../assets/radeon-rx-7900-xtx.jpg"> </div>

            <div class="product-details">
                
                <h3>[Sapphire] NITRO+ AMD Radeon RX 7900 XTX Vapor-X 24GB GDDR6</h3>

                <div class = "header">
                    <h3>152,900৳</h3>
                    <h5>(12 in stock)</h5>
                </div>
                
                <p>Boost Clock: Up to 2680 MHz, Game Clock: Up to 2510 MHz<br>
                    Memory Clock: 20 Gbps Effective<br>
                    RDNA 2 architecture<br>
                    Output: 2x HDMI, 2x DisplayPort<br>
                </p>
             </div>    

            <div class="footer">
                <div class="qty">
                    <button class="decrement" onclick="decrement(this)">-</button>
                    <input type="number" value="0" class="qty-input">
                    <button class="increment" onclick="increment(this)">+</button>
                </div>
                <div class="add-to-cart"> <button>Add to Cart</button> </div>
            </div>
        </div>

        <div div class="product-card">
            <div class="product-image"> <img src="../assets/geforce-rtx-5090.jpg"> </div>

            <div class="product-details">
                
                <h3>[ASUS] ROG Astral GeForce RTX 5090 32GB GDDR7</h3>

                <div class = "header">
                    <h3>730,000৳</h3>
                    <h5>(out of stock)</h5>
                </div>
                
                <p>Engine Clock: 2580 MHz (Boost Clock), 2610 MHz (OC Mode)<br>
                Memory: 32GB GDDR7. 28 Gbps<br>
                CUDA Core: 21760<br>
                AI Performance: 3352 TOPs<br>
                Output: 2x HDMI 2.1b, 3x DisplayPort 2.1b<br>
                </p>
             </div>    

            <div class="footer">
                <div class="qty">
                    <button class="decrement" onclick="decrement(this)">-</button>
                    <input type="number" value="0" class="qty-input">
                    <button class="increment" onclick="increment(this)">+</button>
                </div>
                <div class="add-to-cart"> <button>Add to Cart</button> </div>
            </div>
        </div>

         <div div class="product-card">
            <div class="product-image"> <img src="../assets/ryzen-9-9950x3d.jpg"> </div>

            <div class="product-details">
                
                <h3>[AMD] Ryzen 9 9950X3D</h3>

                <div class = "header">
                    <h3>78,600৳</h3>
                    <h5>(1 in stock)</h5>
                </div>
                
                <p>Clock Speed: 4.3GHz Up to 5.7GHz<br>
                Cores: 16; Threads: 32<br>
                Cache: L1 : 1280KB; L2 : 16MB; L3 : 128MB<br>
                CPU Socket: AM5<br>
                </p>
             </div>    

            <div class="footer">
                <div class="qty">
                    <button class="decrement" onclick="decrement(this)">-</button>
                    <input type="number" value="0" class="qty-input">
                    <button class="increment" onclick="increment(this)">+</button>
                </div>
                <div class="add-to-cart"> <button>Add to Cart</button> </div>
            </div>
        </div>

        <div div class="product-card">
            <div class="product-image"> <img src="../assets/c201-4-500x500.jpg"> </div>

            <div class="product-details">
                
                <h3>[GIGABYTE] C201 Panoramic Ice Mid Tower M-ATX</h3>

                <div class = "header">
                    <h3>5,300৳</h3>
                    <h5>(1 in stock)</h5>
                </div>
                
                <p>Motherboard Support:mini-ITX/m-ATX<br>
                Panoramic Tempered Glass Front and Side Panels<br>
                Connectors: USB 3.0 x2, RGB LED Switch, Power Switch, Audio In & Out<br>
                Pre-installed Fan: 3x 120mm ARGB<br>
                </p>
             </div>    

            <div class="footer">
                <div class="qty">
                    <button class="decrement" onclick="decrement(this)">-</button>
                    <input type="number" value="0" class="qty-input">
                    <button class="increment" onclick="increment(this)">+</button>
                </div>
                <div class="add-to-cart"> <button>Add to Cart</button> </div>
            </div>
        </div>
    </section>
</body>
</html>