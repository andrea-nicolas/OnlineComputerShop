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


// ---------------- DELETE ----------------

function confirmDelete(what) {
    return confirm("Are you sure you want to delete this " + what + "?");
}
