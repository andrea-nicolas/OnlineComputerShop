<?php

// Brand list page + delete handling.

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

        $_SESSION["err"] = "No brand was selected.";

    } else {

        $result = $db->getBrandById($conn, $deleteId);

        if ($result->num_rows == 0) {

            $_SESSION["err"] = "That brand no longer exists.";

        } else {

            $brand = $result->fetch_assoc();

            // products.brand_id is ON DELETE CASCADE in the shared schema,
            // so deleting a brand here would silently delete its products.
            // The delete is blocked instead.
            $row = $db->countProductsInBrand($conn, $deleteId)->fetch_assoc();
            $productCount = $row["total"];

            if ($productCount > 0) {
                $_SESSION["err"] = "\"" . $brand["name"] . "\" cannot be deleted because "
                    . $productCount . " product(s) belong to it. Delete those products first.";
            } else {
                if ($db->deleteBrand($conn, $deleteId)) {
                    $_SESSION["msg"] = "Brand \"" . $brand["name"] . "\" was deleted.";
                } else {
                    $_SESSION["err"] = "The brand could not be deleted.";
                }
            }
        }
    }

    $conn->close();
    header("Location: brand.php");
    exit();
}

// ---------------- LIST ----------------

$brandRows = array();

$result = $db->getAllBrands($conn);
while ($row = $result->fetch_assoc()) {
    $brandRows[] = $row;
}

$conn->close();

?>
