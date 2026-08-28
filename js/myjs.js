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
