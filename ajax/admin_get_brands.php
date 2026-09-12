<?php

// AJAX ENDPOINT - returns the brands of one category as JSON.
//
// Called by loadBrands() in assets/js/admin.js when the category dropdown on the
// product form changes, so the brand list updates without reloading the page.
//
// Request : GET ../../ajax/admin_get_brands.php?category_id=2
// Response: {"success":true,"message":"","brands":[{"id":1,"name":"SanDisk"}]}

include_once "../controllers/admin_authcheck_json.php";
include_once "../models/admin_db.php";

header("Content-Type: application/json");

// the answer that is sent back, filled in below
$response = array();
$response["success"] = false;
$response["message"] = "";
$response["brands"] = array();

// ---------------- VALIDATE THE REQUEST ----------------
// An AJAX request is just an HTTP request, so it is checked exactly like a
// form post. Nothing is trusted because it came from our own JavaScript.

if (!isset($_GET["category_id"]) || $_GET["category_id"] == "") {
    $response["message"] = "No category was chosen.";
    echo json_encode($response);
    exit();
}

if (!is_numeric($_GET["category_id"])) {
    $response["message"] = "The category id must be a number.";
    echo json_encode($response);
    exit();
}

$categoryId = (int) $_GET["category_id"];

// ---------------- READ FROM THE DATABASE ----------------

$db = new mydb();
$conn = $db->openConn();

$result = $db->getCategoryById($conn, $categoryId);

if ($result->num_rows == 0) {
    $conn->close();
    $response["message"] = "That category does not exist.";
    echo json_encode($response);
    exit();
}

$category = $result->fetch_assoc();

// build a normal PHP array first, then turn it into JSON
$data = array();

$result = $db->getBrandsByCategory($conn, $categoryId);
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

$response["success"] = true;
$response["brands"] = $data;

if (count($data) == 0) {
    $response["message"] = "This category has no brand yet.";
}

echo json_encode($response);

// No closing PHP tag on purpose: any space or new line after it would be
// sent along with the JSON and could break JSON.parse() in the browser.
