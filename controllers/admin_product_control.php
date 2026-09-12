<?php

// Product list page + delete handling.
// Deleting a product also deletes its uploaded image file.

include_once "../../controllers/admin_authcheck.php";
include_once "../../models/admin_db.php";
include_once "../../controllers/admin_status_helper.php";

$db = new mydb();
$conn = $db->openConn();

// ---------------- ACTIVE / INACTIVE TOGGLE ----------------
// This is the fallback used when JavaScript is switched off. With JavaScript
// working, toggleStatus() in assets/js/admin.js calls ajax/admin_toggle_status.php instead
// and the row updates without the page reloading. Both paths end up in
// save_product_status(), so they behave identically.

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle"])) {

    $toggleId = 0;
    if (!empty($_POST["toggle_id"]) && is_numeric($_POST["toggle_id"])) {
        $toggleId = (int) $_POST["toggle_id"];
    }

    $newStatus = "";
    if (!empty($_POST["new_status"]) && is_valid_status($_POST["new_status"])) {
        $newStatus = $_POST["new_status"];
    }

    if ($toggleId == 0) {
        $_SESSION["err"] = "No product was selected.";
    } elseif ($newStatus == "") {
        $_SESSION["err"] = "The status must be active or inactive.";
    } else {

        $result = $db->getProductById($conn, $toggleId);

        if ($result->num_rows == 0) {
            $_SESSION["err"] = "That product no longer exists.";
        } else {
            $product = $result->fetch_assoc();
            if (save_product_status($db, $conn, $toggleId, $newStatus)) {
                $_SESSION["msg"] = "\"" . $product["name"] . "\" is now " . $newStatus . ".";
            } else {
                $_SESSION["err"] = "The status could not be saved.";
            }
        }
    }

    $conn->close();
    header("Location: product.php");
    exit();
}

// ---------------- DELETE ----------------

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {

    $deleteId = 0;
    if (!empty($_POST["delete_id"]) && is_numeric($_POST["delete_id"])) {
        $deleteId = (int) $_POST["delete_id"];
    }

    if ($deleteId == 0) {

        $_SESSION["err"] = "No product was selected.";

    } else {

        $result = $db->getProductById($conn, $deleteId);

        if ($result->num_rows == 0) {

            $_SESSION["err"] = "That product no longer exists.";

        } else {

            $product = $result->fetch_assoc();
            $oldImage = $product["image_path"];

            if ($db->deleteProduct($conn, $deleteId)) {

                // remove the image file from the uploads folder
                if ($oldImage != "" && file_exists("../../" . $oldImage)) {
                    unlink("../../" . $oldImage);
                }

                $_SESSION["msg"] = "Product \"" . $product["name"] . "\" was deleted.";

            } else {
                $_SESSION["err"] = "The product could not be deleted.";
            }
        }
    }

    $conn->close();
    header("Location: product.php");
    exit();
}

// ---------------- LIST ----------------

$productRows = array();

$result = $db->getAllProducts($conn);
while ($row = $result->fetch_assoc()) {
    $productRows[] = $row;
}

$conn->close();

?>
