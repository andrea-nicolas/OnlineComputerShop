<?php

// Add / edit a brand. A brand always belongs to one category.

include_once "../control/authcheck.php";
include_once "../model/db.php";
include_once "../control/validation.php";

$db = new mydb();
$conn = $db->openConn();

$nameErr = "";
$categoryErr = "";
$name = "";
$categoryId = "";
$editId = 0;
$formTitle = "Add Brand";

// ---------------- IS THIS AN EDIT? ----------------

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

    $editId = (int) $_GET["id"];
    $result = $db->getBrandById($conn, $editId);

    if ($result->num_rows == 0) {
        $conn->close();
        $_SESSION["err"] = "That brand does not exist.";
        header("Location: brand.php");
        exit();
    }

    $formTitle = "Edit Brand";

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $categoryId = $row["category_id"];
    }
}

// ---------------- SAVE ----------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ---- name ----
    if (empty($_POST["name"])) {
        $nameErr = "Brand name is required";
    } else {
        $name = test_input($_POST["name"]);

        if (strlen($name) < 2) {
            $nameErr = "Brand name must be at least 2 characters";
        } elseif (strlen($name) > 100) {
            $nameErr = "Brand name cannot be longer than 100 characters";
        } elseif (!preg_match("/^[a-zA-Z0-9 &\-'.]+$/", $name)) {
            $nameErr = "Only letters, numbers, spaces, &, -, . and ' are allowed";
        }
    }

    // ---- category (required) ----
    $categoryId = "";
    if (empty($_POST["category_id"])) {
        $categoryErr = "Please choose a category";
    } elseif (!is_numeric($_POST["category_id"])) {
        $categoryErr = "Invalid category";
    } else {
        $categoryId = (int) $_POST["category_id"];

        $result = $db->getCategoryById($conn, $categoryId);
        if ($result->num_rows == 0) {
            $categoryErr = "That category does not exist";
            $categoryId = "";
        }
    }

    // ---- the same brand name twice in one category ----
    if ($nameErr == "" && $categoryErr == "") {
        $row = $db->countBrandByName($conn, $name, $categoryId, $editId)->fetch_assoc();
        if ($row["total"] > 0) {
            $nameErr = "This brand already exists in the chosen category";
        }
    }

    // ---- save ----
    if ($nameErr == "" && $categoryErr == "") {

        if ($editId > 0) {
            $done = $db->updateBrand($conn, $editId, $name, $categoryId);
            $doneMsg = "Brand \"" . $name . "\" was updated.";
        } else {
            $done = $db->insertBrand($conn, $name, $categoryId);
            $doneMsg = "Brand \"" . $name . "\" was added.";
        }

        if ($done) {
            $conn->close();
            $_SESSION["msg"] = $doneMsg;
            header("Location: brand.php");
            exit();
        } else {
            $nameErr = "The brand could not be saved. Please try again.";
        }
    }
}

// ---------------- CATEGORY DROPDOWN ----------------
// Every category is listed. Sub-categories are shown as "Parent > Child"
// so it is clear where the brand is being placed.

$categoryRows = array();

$result = $db->getAllCategories($conn);
while ($row = $result->fetch_assoc()) {
    $categoryRows[] = $row;
}

$conn->close();

?>
