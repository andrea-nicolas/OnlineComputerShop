function isValidQuantity(quantity) {
    return /^\d+$/.test(quantity) && parseInt(quantity, 10) > 0;
}

function qtyCheck(productId) {
    const quantity = document.getElementById('quantity-' + productId).value;

    if (!isValidQuantity(quantity)) {
        document.getElementById('qtyErr-' + productId).innerHTML = "Please enter a valid positive number.";
    } else {
        document.getElementById('qtyErr-' + productId).innerHTML = "";
    }
}