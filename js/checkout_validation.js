

function validatePaymentMethod() {
    var options = document.getElementsByName("payment_method");
    var errorBox = document.getElementById("payment_method-error");
    var chosen = false;
    var i;

    for (i = 0; i < options.length; i++) {
        if (options[i].checked) {
            chosen = true;
        }
    }

    if (errorBox) {
        errorBox.innerHTML = chosen ? "" : "Please choose a payment method.";
    }

    return chosen;
}

function validateCheckoutForm() {
    if (!validatePaymentMethod()) {
        return false;
    }
    return confirm("Place this order?");
}

function selectPayment() {
    var options = document.getElementsByName("payment_method");
    var i, card;

    for (i = 0; i < options.length; i++) {
        card = document.getElementById("pay-card-" + options[i].value);
        if (card) {
            card.className = options[i].checked ? "pay-option selected" : "pay-option";
        }
    }
    validatePaymentMethod();
}

window.onload = function () {
    var options = document.getElementsByName("payment_method");
    var i;

    for (i = 0; i < options.length; i++) {
        options[i].onclick = selectPayment;
    }
    selectPayment();
};
