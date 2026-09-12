

function csrfToken() {
    var tag = document.querySelector("meta[name='csrf-token']");
    return tag ? tag.getAttribute("content") : "";
}

function cartRequest(body, whenDone) {
    body = body + "&csrf_token=" + encodeURIComponent(csrfToken());


    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            whenDone(data);
        }
    };

    xhttp.open("POST", "../control/api_cart.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(body);
}

function cartMessage(text, isError) {

    var box = document.getElementById("cart-message");
    if (!box) {
        return;
    }

    if (text === "") {
        box.style.display = "none";
        return;
    }

    box.className = isError ? "alert alert-error" : "alert alert-success";
    box.innerHTML = "";
    box.appendChild(document.createTextNode(text));
    box.style.display = "";
}

function cartTotals(data) {

    var i;
    var totals = document.getElementsByClassName("cart-total-value");

    for (i = 0; i < totals.length; i++) {
        totals[i].innerHTML = "";
        totals[i].appendChild(document.createTextNode(data.total_text));
    }

    var counter = document.getElementById("cart-count");
    if (counter) {
        counter.innerHTML = "";
        counter.appendChild(document.createTextNode(data.count));
    }
}

function cartUpdate(form) {

    var cartId = form.cart_id.value;
    var qty    = form.quantity.value;
    var max    = parseInt(form.quantity.max, 10);

    if (qty === "" || isNaN(qty) || qty.indexOf(".") > -1 || parseInt(qty, 10) < 1) {
        cartMessage("The quantity has to be a whole number, at least 1.", true);
        return false;
    }
    if (parseInt(qty, 10) > max) {
        cartMessage("Only " + max + " of this product are in stock.", true);
        return false;
    }

    cartRequest("action=update&cart_id=" + cartId + "&quantity=" + qty, function (data) {

        cartMessage(data.message, !data.ok);

        if (data.ok) {
            var cell = document.getElementById("line-total-" + data.cart_id);
            if (cell) {
                cell.innerHTML = "";
                cell.appendChild(document.createTextNode(data.line_text));
            }
            cartTotals(data);
        }
    });

    return false;
}

function cartRemove(form, name) {

    if (!confirm("Remove \"" + name + "\" from your cart?")) {
        return false;
    }

    var cartId = form.cart_id.value;

    cartRequest("action=remove&cart_id=" + cartId, function (data) {

        cartMessage(data.message, !data.ok);

        if (data.ok) {
            var row = document.getElementById("cart-row-" + data.cart_id);
            if (row) {
                row.parentNode.removeChild(row);
            }
            cartTotals(data);

            if (data.count === 0) {
                window.location.reload();
            }
        }
    });

    return false;
}
