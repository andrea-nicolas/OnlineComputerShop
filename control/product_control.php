<?php

// Product list page + delete handling.
// Deleting a product also deletes its uploaded image file.

include_once "../control/authcheck.php";
include_once "../model/db.php";

$db = new mydb();
$conn = $db->openConn();

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
                if ($oldImage != "" && file_exists($oldImage)) {
                    unlink($oldImage);
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
