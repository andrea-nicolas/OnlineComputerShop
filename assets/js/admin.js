// Client side checks only. Every one of these rules is checked again in PHP,
// because JavaScript can be switched off in the browser.

function showError(id, message) {
    var box = document.getElementById(id);
    if (box != null) {
        box.innerHTML = message;
    }
}

function clearError(id) {
    var box = document.getElementById(id);
    if (box != null) {
        box.innerHTML = "";
    }
}

function getValue(id) {
    var field = document.getElementById(id);
    if (field == null) {
        return "";
    }
    return field.value;
}


// ---------------- LOGIN FORM ----------------

function emailvalidation() {
    var email = getValue("email");

    if (email == "") {
        showError("email-error", "Email is required");
        return false;
    }
    if (email.indexOf("@") < 0 || email.indexOf(".") < 0) {
        showError("email-error", "Please enter a valid email");
        return false;
    }

    clearError("email-error");
    return true;
}

function passwordvalidation() {
    var password = getValue("password");

    if (password == "") {
        showError("password-error", "Password is required");
        return false;
    }

    clearError("password-error");
    return true;
}

function loginvalidation() {
    var ok1 = emailvalidation();
    var ok2 = passwordvalidation();

    if (ok1 == false || ok2 == false) {
        return false;
    }
    return true;
}


// ---------------- SHARED NAME CHECK ----------------

function namevalidation(label) {
    var name = getValue("name");

    if (name == "") {
        showError("name-error", label + " name is required");
        return false;
    }
    if (name.length < 2) {
        showError("name-error", label + " name must be at least 2 characters");
        return false;
    }
    if (name.length > 100) {
        showError("name-error", label + " name cannot be longer than 100 characters");
        return false;
    }

    clearError("name-error");
    return true;
}


// ---------------- CATEGORY FORM ----------------

function categoryvalidation() {
    return namevalidation("Category");
}


// ---------------- BRAND FORM ----------------

function categorypicked() {
    var category = getValue("category_id");

    if (category == "") {
        showError("category-error", "Please choose a category");
        return false;
    }

    clearError("category-error");
    return true;
}

function brandvalidation() {
    var ok1 = namevalidation("Brand");
    var ok2 = categorypicked();

    if (ok1 == false || ok2 == false) {
        return false;
    }
    return true;
}


// ---------------- PRODUCT FORM ----------------

function pricevalidation() {
    var price = getValue("price");

    if (price == "") {
        showError("price-error", "Price is required");
        return false;
    }
    if (isNaN(price)) {
        showError("price-error", "Price must be a number");
        return false;
    }
    if (Number(price) <= 0) {
        showError("price-error", "Price must be greater than 0");
        return false;
    }

    clearError("price-error");
    return true;
}

function stockvalidation() {
    var stock = getValue("stock");

    if (stock == "") {
        showError("stock-error", "Stock quantity is required");
        return false;
    }
    if (isNaN(stock)) {
        showError("stock-error", "Stock must be a number");
        return false;
    }
    if (Number(stock) < 0) {
        showError("stock-error", "Stock cannot be negative");
        return false;
    }
    if (Number(stock) != parseInt(stock)) {
        showError("stock-error", "Stock must be a whole number");
        return false;
    }

    clearError("stock-error");
    return true;
}

function brandpicked() {
    var brand = getValue("brand_id");

    if (brand == "") {
        showError("brand-error", "Please choose a brand");
        return false;
    }

    clearError("brand-error");
    return true;
}

function imagevalidation() {
    var field = document.getElementById("image");

    if (field == null || field.files.length == 0) {
        // no new file chosen - PHP decides if that is allowed
        clearError("image-error");
        return true;
    }

    var file = field.files[0];
    var fileName = file.name.toLowerCase();

    var isJpg = fileName.indexOf(".jpg") == fileName.length - 4;
    var isJpeg = fileName.indexOf(".jpeg") == fileName.length - 5;
    var isPng = fileName.indexOf(".png") == fileName.length - 4;

    if (isJpg == false && isJpeg == false && isPng == false) {
        showError("image-error", "Only JPEG and PNG images are allowed");
        return false;
    }

    if (file.size > 2097152) {
        showError("image-error", "The image must be 2MB or smaller");
        return false;
    }

    clearError("image-error");
    return true;
}

// The "Load brands of this category" button submits the form on purpose
// without saving, so the form checks below must not block it.
var skipValidation = false;

function markReload() {
    skipValidation = true;
}

function productvalidation() {

    if (skipValidation == true) {
        skipValidation = false;
        return true;
    }

    var ok1 = namevalidation("Product");
    var ok2 = categorypicked();
    var ok3 = brandpicked();
    var ok4 = pricevalidation();
    var ok5 = stockvalidation();
    var ok6 = imagevalidation();

    if (ok1 == false || ok2 == false || ok3 == false || ok4 == false
        || ok5 == false || ok6 == false) {
        return false;
    }
    return true;
}


// ---------------- DELETE ----------------

function confirmDelete(what) {
    return confirm("Are you sure you want to delete this " + what + "?");
}


// =====================================================================
// AJAX + JSON
// ---------------------------------------------------------------------
// When the category dropdown on the product form changes, the brands of
// that category are fetched from ajax/admin_get_brands.php. The server answers
// with JSON, JavaScript reads it and rebuilds the brand dropdown, so the
// page is never reloaded.
//
// The plain "Load brands of this category" submit button is still in the
// form. It is hidden while AJAX is working and shown again if the request
// fails, so the page still works with JavaScript switched off.
// =====================================================================

function showReloadButton() {
    var btn = document.getElementById("reloadbtn");
    if (btn != null) {
        btn.style.display = "inline-block";
    }
}

function hideReloadButton() {
    var btn = document.getElementById("reloadbtn");
    if (btn != null) {
        btn.style.display = "none";
    }
}

function setBrandStatus(message) {
    var box = document.getElementById("brand-status");
    if (box != null) {
        box.innerHTML = message;
    }
}

function loadBrands() {

    var categoryId = getValue("category_id");
    var brandBox = document.getElementById("brand_id");

    if (brandBox == null) {
        return;
    }

    clearError("brand-error");

    if (categoryId == "") {
        brandBox.innerHTML = '<option value="">-- Choose a category first --</option>';
        setBrandStatus("");
        return;
    }

    // remember what was selected, so an edit keeps its brand if it is
    // still one of the brands of the newly chosen category
    var previous = brandBox.value;

    brandBox.disabled = true;
    setBrandStatus("Loading brands...");

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {

            brandBox.disabled = false;

            var data = JSON.parse(this.responseText);

            if (data.success == false) {
                brandBox.innerHTML = '<option value="">-- ' + data.message + ' --</option>';
                setBrandStatus("");
                showError("brand-error", data.message);
                return;
            }

            if (data.brands.length == 0) {
                brandBox.innerHTML = '<option value="">-- This category has no brand yet --</option>';
                setBrandStatus("Add a brand for this category first.");
                return;
            }

            var html = '<option value="">-- Choose a brand --</option>';

            for (var i = 0; i < data.brands.length; i++) {
                var brand = data.brands[i];
                html = html + '<option value="' + brand.id + '"';
                if (brand.id == previous) {
                    html = html + ' selected';
                }
                html = html + '>' + brand.name + '</option>';
            }

            brandBox.innerHTML = html;
            setBrandStatus(data.brands.length + " brand(s) loaded for this category.");

        } else if (this.readyState == 4) {

            // the request finished but not with 200 OK
            brandBox.disabled = false;
            setBrandStatus("");
            showError("brand-error", "Could not load the brands. Use the button below instead.");
            showReloadButton();
        }
    };

    xhttp.onerror = function () {
        brandBox.disabled = false;
        setBrandStatus("");
        showError("brand-error", "Could not reach the server. Use the button below instead.");
        showReloadButton();
    };

    xhttp.open("GET", "../../ajax/admin_get_brands.php?category_id=" + categoryId, true);
    xhttp.send();
}


// ---------------------------------------------------------------------
// AJAX: switch a product between active and inactive from the product list
//
// This one uses POST, because it changes data in the database. The button
// sits inside a normal form, and returning false stops that form from
// submitting so the page never reloads. With JavaScript switched off this
// function never runs, the form submits as usual, and product_control.php
// saves the status instead.
// ---------------------------------------------------------------------

function showAjaxMessage(cssClass, text) {
    var box = document.getElementById("ajax-message");
    if (box != null) {
        box.innerHTML = '<div class="' + cssClass + '">' + text + "</div>";
    }
}

function toggleStatus(productId) {

    var hidden = document.getElementById("statusval-" + productId);
    var badge = document.getElementById("statusbadge-" + productId);
    var button = document.getElementById("statusbtn-" + productId);

    if (hidden == null || badge == null || button == null) {
        // something is missing, let the plain form post handle it
        return true;
    }

    var wanted = hidden.value;

    button.disabled = true;
    button.value = "Saving...";

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {

            button.disabled = false;
            var data = JSON.parse(this.responseText);

            if (data.success == false) {
                button.value = wanted == "active" ? "Set active" : "Set inactive";
                showAjaxMessage("alert-error", data.message);
                return;
            }

            // redraw the row from what the server actually saved
            if (data.status == "inactive") {
                badge.className = "badge badge-off";
                badge.innerHTML = "Inactive";
                hidden.value = "active";
                button.value = "Set active";
            } else {
                badge.className = "badge badge-ok";
                badge.innerHTML = "Active";
                hidden.value = "inactive";
                button.value = "Set inactive";
            }

            showAjaxMessage("alert-success", data.message);

        } else if (this.readyState == 4) {

            button.disabled = false;
            button.value = wanted == "active" ? "Set active" : "Set inactive";
            showAjaxMessage("alert-error",
                "Could not save the status. Please reload the page and try again.");
        }
    };

    xhttp.onerror = function () {
        button.disabled = false;
        button.value = wanted == "active" ? "Set active" : "Set inactive";
        showAjaxMessage("alert-error",
            "Could not reach the server. Please reload the page and try again.");
    };

    xhttp.open("POST", "../../ajax/admin_toggle_status.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("product_id=" + productId + "&status=" + wanted);

    // stop the form around the button from submitting
    return false;
}


// JavaScript is clearly working if this line runs, so the plain submit
// button that does the same job is not needed.
document.addEventListener("DOMContentLoaded", function () {
    hideReloadButton();
});
