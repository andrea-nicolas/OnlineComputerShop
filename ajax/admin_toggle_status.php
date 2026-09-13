<?php

// AJAX ENDPOINT - switches one product between active and inactive.
//
// Called by toggleStatus() in assets/js/admin.js from the product list, so the row
// updates in place without the page reloading.
//
// Request : POST ../../ajax/admin_toggle_status.php   body: product_id=5&status=inactive
// Response: {"success":true,"message":"...","product_id":5,"status":"inactive"}
//
// POST is used, not GET, because this changes data in the database.

include_once "../controllers/admin_authcheck_json.php";
include_once "../models/admin_db.php";
include_once "../controllers/admin_status_helper.php";

header("Content-Type: application/json");

$response = array();
$response["success"] = false;
$response["message"] = "";
$response["product_id"] = 0;
$response["status"] = "";

// ---------------- VALIDATE THE REQUEST ----------------
// An AJAX request is checked exactly like a normal form post. Nothing is
// trusted because it came from our own JavaScript.

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $response["message"] = "This endpoint only accepts POST.";
    echo json_encode($response);
    exit();
}

if (empty($_POST["product_id"]) || !is_numeric($_POST["product_id"])) {
    $response["message"] = "A valid product id is required.";
    echo json_encode($response);
    exit();
}

if (empty($_POST["status"]) || !is_valid_status($_POST["status"])) {
    $response["message"] = "The status must be active or inactive.";
    echo json_encode($response);
    exit();
}

$productId = (int) $_POST["product_id"];
$status = $_POST["status"];

// ---------------- SAVE ----------------

$db = new mydb();
$conn = $db->openConn();

$result = $db->getProductById($conn, $productId);

if ($result->num_rows == 0) {
    $conn->close();
    $response["message"] = "That product does not exist.";
    echo json_encode($response);
    exit();
}

$product = $result->fetch_assoc();

if (save_product_status($db, $conn, $productId, $status)) {

    $response["success"] = true;
    $response["product_id"] = $productId;
    $response["status"] = $status;
    $response["message"] = "\"" . $product["name"] . "\" is now " . $status . ".";

} else {
    $response["message"] = "The status could not be saved.";
}

$conn->close();

echo json_encode($response);

// No closing PHP tag on purpose: any space or new line after it would be
// sent along with the JSON and could break JSON.parse() in the browser.
