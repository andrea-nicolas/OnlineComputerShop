<?php

// Add / edit a product.
//
// The brand dropdown only shows brands of the chosen category. There is no
// AJAX here: choosing a category simply submits the form back to itself with
// the "reload" button, the brand list is rebuilt from the database, and every
// value the user already typed is kept.

include_once "../control/authcheck.php";
include_once "../model/db.php";
include_once "../control/validation.php";

$db = new mydb();
$conn = $db->openConn();

// error messages
$nameErr = "";
$priceErr = "";
$categoryErr = "";
$brandErr = "";
$stockErr = "";
$imageErr = "";
$descriptionErr = "";
$reviewErr = "";

// form values
$name = "";
$description = "";
$review = "";
$price = "";
$categoryId = "";
$brandId = "";
$stock = "";
$currentImage = "";

$editId = 0;
$formTitle = "Add Product";

// The form has two submit buttons: "Save" and "Load brands of this category".
// Only the Save button validates and writes to the database. Any other submit
// just rebuilds the brand list and keeps everything the user typed.
$isSave = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    $isSave = true;
}

// ---------------- IS THIS AN EDIT? ----------------

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

    $editId = (int) $_GET["id"];
    $result = $db->getProductById($conn, $editId);

    if ($result->num_rows == 0) {
        $conn->close();
        $_SESSION["err"] = "That product does not exist.";
        header("Location: product.php");
        exit();
    }

    $formTitle = "Edit Product";
    $row = $result->fetch_assoc();
    $currentImage = $row["image_path"];

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $name = $row["name"];
        $description = $row["description"];
        $review = $row["manufacturer_review"];
        $price = $row["price"];
        $categoryId = $row["category_id"];
        $brandId = $row["brand_id"];
        $stock = $row["stock"];
    }
}

// ---------------- READ WHAT WAS POSTED ----------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["name"])) {
        $name = test_input($_POST["name"]);
    } else {
        $name = "";
    }

    if (!empty($_POST["description"])) {
        $description = test_input($_POST["description"]);
    } else {
        $description = "";
    }

    if (!empty($_POST["manufacturer_review"])) {
        $review = test_input($_POST["manufacturer_review"]);
    } else {
        $review = "";
    }

    if (isset($_POST["price"])) {
        $price = test_input($_POST["price"]);
    }

    if (isset($_POST["stock"])) {
        $stock = test_input($_POST["stock"]);
    }

    $categoryId = "";
    if (!empty($_POST["category_id"]) && is_numeric($_POST["category_id"])) {
        $categoryId = (int) $_POST["category_id"];
    }

    $brandId = "";
    if (!empty($_POST["brand_id"]) && is_numeric($_POST["brand_id"])) {
        $brandId = (int) $_POST["brand_id"];
    }
}

// ---------------- VALIDATE AND SAVE ----------------

if ($isSave == true) {

    // ---- name ----
    if ($name == "") {
        $nameErr = "Product name is required";
    } elseif (strlen($name) < 2) {
        $nameErr = "Product name must be at least 2 characters";
    } elseif (strlen($name) > 100) {
        $nameErr = "Product name cannot be longer than 100 characters";
    } else {
        $row = $db->countProductByName($conn, $name, $editId)->fetch_assoc();
        if ($row["total"] > 0) {
            $nameErr = "A product with this name already exists";
        }
    }

    // ---- description / manufacturer review (optional, length only) ----
    if (strlen($description) > 1000) {
        $descriptionErr = "Description cannot be longer than 1000 characters";
    }
    if (strlen($review) > 500) {
        $reviewErr = "Manufacturer review cannot be longer than 500 characters";
    }

    // ---- price ----
    if ($price == "") {
        $priceErr = "Price is required";
    } elseif (!is_numeric($price)) {
        $priceErr = "Price must be a number";
    } elseif ($price <= 0) {
        $priceErr = "Price must be greater than 0";
    } elseif ($price > 99999999) {
        $priceErr = "Price is too large";
    }

    // ---- stock ----
    if ($stock == "") {
        $stockErr = "Stock quantity is required";
    } elseif (!is_numeric($stock)) {
        $stockErr = "Stock must be a number";
    } elseif ($stock < 0) {
        $stockErr = "Stock cannot be negative";
    } elseif ($stock != (int) $stock) {
        $stockErr = "Stock must be a whole number";
    }

    // ---- category ----
    if ($categoryId == "") {
        $categoryErr = "Please choose a category";
    } else {
        $result = $db->getCategoryById($conn, $categoryId);
        if ($result->num_rows == 0) {
            $categoryErr = "That category does not exist";
            $categoryId = "";
        }
    }

    // ---- brand (must belong to the chosen category) ----
    if ($brandId == "") {
        $brandErr = "Please choose a brand";
    } elseif ($categoryId != "") {
        $result = $db->getBrandById($conn, $brandId);
        if ($result->num_rows == 0) {
            $brandErr = "That brand does not exist";
            $brandId = "";
        } else {
            $brandRow = $result->fetch_assoc();
            if ($brandRow["category_id"] != $categoryId) {
                $brandErr = "That brand does not belong to the chosen category";
                $brandId = "";
            }
        }
    }

    // ---- image ----
    // Required when adding. When editing, leaving it empty keeps the old image.
    $newImagePath = $currentImage;
    $uploadedTempFile = "";
    $uploadedNewName = "";

    $hasFile = false;
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
        $hasFile = true;
    }

    if ($hasFile == false) {

        if ($editId == 0) {
            $imageErr = "A product image is required";
        }

    } else {

        if ($_FILES["image"]["error"] != UPLOAD_ERR_OK) {

            $imageErr = "The file could not be uploaded. Please try again.";

        } elseif ($_FILES["image"]["size"] > 2097152) {

            $imageErr = "The image must be 2MB or smaller";

        } else {

            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

            if ($ext != "jpg" && $ext != "jpeg" && $ext != "png") {

                $imageErr = "Only JPEG and PNG images are allowed";

            } else {

                // check the real content of the file, not just its name
                $imageInfo = getimagesize($_FILES["image"]["tmp_name"]);

                if ($imageInfo == false) {
                    $imageErr = "That file is not a real image";
                } elseif ($imageInfo["mime"] != "image/jpeg" && $imageInfo["mime"] != "image/png") {
                    $imageErr = "Only JPEG and PNG images are allowed";
                } else {
                    // a unique name so two uploads never overwrite each other
                    $uploadedNewName = "p" . time() . rand(100, 999) . "." . $ext;
                    $uploadedTempFile = $_FILES["image"]["tmp_name"];
                    $newImagePath = "../uploads/products/" . $uploadedNewName;
                }
            }
        }
    }

    // ---- everything is valid, write to the database ----
    if ($nameErr == "" && $priceErr == "" && $categoryErr == "" && $brandErr == ""
        && $stockErr == "" && $imageErr == "" && $descriptionErr == "" && $reviewErr == "") {

        $moveOk = true;

        // move the uploaded file into the uploads folder first
        if ($uploadedTempFile != "") {
            $moveOk = move_uploaded_file($uploadedTempFile, "../uploads/products/" . $uploadedNewName);
            if ($moveOk == false) {
                $imageErr = "The image could not be saved to the uploads folder";
            }
        }

        if ($moveOk) {

            $priceValue = (float) $price;
            $stockValue = (int) $stock;

            if ($editId > 0) {
                $done = $db->updateProduct($conn, $editId, $name, $description, $review,
                    $priceValue, $categoryId, $brandId, $newImagePath, $stockValue);
                $doneMsg = "Product \"" . $name . "\" was updated.";
            } else {
                $done = $db->insertProduct($conn, $name, $description, $review,
                    $priceValue, $categoryId, $brandId, $newImagePath, $stockValue);
                $doneMsg = "Product \"" . $name . "\" was added.";
            }

            if ($done) {

                // the new image replaced an old one, so remove the old file
                if ($uploadedNewName != "" && $currentImage != ""
                    && $currentImage != $newImagePath && file_exists($currentImage)) {
                    unlink($currentImage);
                }

                $conn->close();
                $_SESSION["msg"] = $doneMsg;
                header("Location: product.php");
                exit();

            } else {
                $nameErr = "The product could not be saved. Please try again.";
            }
        }
    }
}

// ---------------- DROPDOWN DATA ----------------

// every category, for the category dropdown
$categoryRows = array();
$result = $db->getAllCategories($conn);
while ($row = $result->fetch_assoc()) {
    $categoryRows[] = $row;
}

// brands of the chosen category only
$brandRows = array();
if ($categoryId != "") {
    $result = $db->getBrandsByCategory($conn, $categoryId);
    while ($row = $result->fetch_assoc()) {
        $brandRows[] = $row;
    }
}

// if the chosen brand is not in this category any more, forget it
$brandStillValid = false;
foreach ($brandRows as $row) {
    if ($row["id"] == $brandId) {
        $brandStillValid = true;
    }
}
if ($brandStillValid == false) {
    $brandId = "";
}

$conn->close();

?>
