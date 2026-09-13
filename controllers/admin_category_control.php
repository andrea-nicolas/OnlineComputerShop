<?php

// Category list page + delete handling.

include_once "../../controllers/admin_authcheck.php";
include_once "../../models/admin_db.php";

$db = new mydb();
$conn = $db->openConn();

// ---------------- DELETE ----------------

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {

    $deleteId = 0;
    if (!empty($_POST["delete_id"]) && is_numeric($_POST["delete_id"])) {
        $deleteId = (int) $_POST["delete_id"];
    }

    if ($deleteId == 0) {

        $_SESSION["err"] = "No category was selected.";

    } else {

        // the category must still exist
        $result = $db->getCategoryById($conn, $deleteId);

        if ($result->num_rows == 0) {

            $_SESSION["err"] = "That category no longer exists.";

        } else {

            $category = $result->fetch_assoc();

            // count everything that depends on this category
            $row = $db->countChildCategories($conn, $deleteId)->fetch_assoc();
            $childCount = $row["total"];

            $row = $db->countProductsInCategory($conn, $deleteId)->fetch_assoc();
            $productCount = $row["total"];

            $row = $db->countBrandsInCategory($conn, $deleteId)->fetch_assoc();
            $brandCount = $row["total"];

            if ($childCount > 0) {
                $_SESSION["err"] = "\"" . $category["name"] . "\" cannot be deleted because it has "
                    . $childCount . " sub-category(s). Delete or move them first.";
            } elseif ($productCount > 0) {
                $_SESSION["err"] = "\"" . $category["name"] . "\" cannot be deleted because "
                    . $productCount . " product(s) belong to it. Delete those products first.";
            } elseif ($brandCount > 0) {
                $_SESSION["err"] = "\"" . $category["name"] . "\" cannot be deleted because "
                    . $brandCount . " brand(s) belong to it. Delete those brands first.";
            } else {
                if ($db->deleteCategory($conn, $deleteId)) {
                    $_SESSION["msg"] = "Category \"" . $category["name"] . "\" was deleted.";
                } else {
                    $_SESSION["err"] = "The category could not be deleted.";
                }
            }
        }
    }

    // reload the page so a browser refresh does not repeat the delete
    $conn->close();
    header("Location: category.php");
    exit();
}

// ---------------- LIST ----------------

$categoryRows = array();

$result = $db->getAllCategories($conn);
while ($row = $result->fetch_assoc()) {
    $categoryRows[] = $row;
}

$conn->close();

?>
