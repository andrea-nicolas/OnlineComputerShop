function isValidQuantity(quantity) {
    return /^\d+$/.test(quantity) && parseInt(quantity, 10) > 0;
}

function qtyCheck(productId) {
    var quantity = document.getElementById('quantity-' + productId).value;

    if (!isValidQuantity(quantity)) {
        document.getElementById('qtyErr-' + productId).innerHTML = "Please enter a valid positive number.";
    } else {
        document.getElementById('qtyErr-' + productId).innerHTML = "";
    }
}

function updatePriceLabel(val) {
    document.getElementById("price-range-val").innerText = val;
}

function filterProducts() {
    var q = document.getElementById("search-input") ? document.getElementById("search-input").value : "";
    var cat = document.getElementById("filter-category") ? document.getElementById("filter-category").value : "";
    var brand = document.getElementById("filter-brand") ? document.getElementById("filter-brand").value : "";
    var maxPrice = document.getElementById("filter-price-range") ? document.getElementById("filter-price-range").value : "";

    var xttp = new XMLHttpRequest();
    xttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("product-grid").innerHTML = this.responseText;
        }
    };
    xttp.open("GET", "../controllers/searchController.php?q=" + q + "&category=" + cat + "&brand=" + brand + "&max_price=" + maxPrice, true);
    xttp.send();
}

function addToCart(productId) {
    var qty = document.getElementById("quantity-" + productId).value;

    var xttp = new XMLHttpRequest();
    xttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var parts = this.responseText.split("|");
            if (parts[0] == "SUCCESS") {
                document.getElementById("cart-count").innerHTML = parts[1];
                document.getElementById("qtyErr-" + productId).style.color = "green";
                document.getElementById("qtyErr-" + productId).innerHTML = parts[2];
            } else {
                document.getElementById("qtyErr-" + productId).style.color = "red";
                document.getElementById("qtyErr-" + productId).innerHTML = parts[1];
            }
        }
    };
    xttp.open("POST", "../controllers/cartsController.php?action=add", true);
    xttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xttp.send("product_id=" + productId + "&quantity=" + qty);
}

function updateQuantity(productId, change) {
    var currentQty = parseInt(document.getElementById("qty-" + productId).innerText);
    var newQty = currentQty + change;
    var price = parseFloat(document.getElementById("unit-price-" + productId).innerText);

    if (newQty <= 0) {
        removeItem(productId);
        return;
    }

    var xttp = new XMLHttpRequest();
    xttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var parts = this.responseText.split("|");
            document.getElementById("qty-" + productId).innerText = newQty;
            document.getElementById("subtotal-" + productId).innerText = (price * newQty);
            document.getElementById("summary-items").innerText = parts[0];
            document.getElementById("summary-subtotal").innerText = parts[1];
            document.getElementById("summary-total").innerText = parts[2];
        }
    };
    xttp.open("POST", "../controllers/cartsController.php?action=update", true);
    xttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xttp.send("product_id=" + productId + "&quantity=" + newQty);
}

function removeItem(productId) {
    var xttp = new XMLHttpRequest();
    xttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var parts = this.responseText.split("|");
            var itemElement = document.getElementById("cart-item-" + productId);
            if (itemElement) {
                itemElement.remove();
            }
            document.getElementById("summary-items").innerText = parts[0];
            document.getElementById("summary-subtotal").innerText = parts[1];
            document.getElementById("summary-total").innerText = parts[2];

            if (parseInt(parts[0]) == 0) {
                document.getElementById("cart-items-container").innerHTML = "<p>Your cart is empty.</p>";
            }
        }
    };
    xttp.open("POST", "../controllers/cartsController.php?action=remove", true);
    xttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xttp.send("product_id=" + productId);
}