<?php

// Add / edit a category.
// The same file handles both: if an id is in the URL it is an edit,
// otherwise it is a new category.

include_once "../control/authcheck.php";
include_once "../model/db.php";
include_once "../control/validation.php";

$db = new mydb();
$conn = $db->openConn();

// error messages and form values
$nameErr = "";
$parentErr = "";
$name = "";
$parentId = "";
$editId = 0;
$formTitle = "Add Category";

// ---------------- IS THIS AN EDIT? ----------------

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

    $editId = (int) $_GET["id"];
    $result = $db->getCategoryById($conn, $editId);

    if ($result->num_rows == 0) {
        $conn->close();
        $_SESSION["err"] = "That category does not exist.";
        header("Location: category.php");
        exit();
    }

    $formTitle = "Edit Category";

    // fill the form with the saved values (only on the first visit,
    // otherwise the user's own typing would be wiped out)
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $parentId = $row["parent_id"];
    }
}

// ---------------- SAVE ----------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ---- name ----
    if (empty($_POST["name"])) {
        $nameErr = "Category name is required";
    } else {
        $name = test_input($_POST["name"]);

        if (strlen($name) < 2) {
            $nameErr = "Category name must be at least 2 characters";
        } elseif (strlen($name) > 100) {
            $nameErr = "Category name cannot be longer than 100 characters";
        } elseif (!preg_match("/^[a-zA-Z0-9 &\-']+$/", $name)) {
            $nameErr = "Only letters, numbers, spaces, &, - and ' are allowed";
        }
    }

    // ---- parent category (optional) ----
    $parentId = "";
    if (!empty($_POST["parent_id"])) {

        if (!is_numeric($_POST["parent_id"])) {
            $parentErr = "Invalid parent category";
        } else {
            $parentId = (int) $_POST["parent_id"];

            // the parent must exist
            $result = $db->getCategoryById($conn, $parentId);
            if ($result->num_rows == 0) {
                $parentErr = "That parent category does not exist";
                $parentId = "";
            } elseif ($parentId == $editId) {
                $parentErr = "A category cannot be its own parent";
                $parentId = "";
            } else {
                // only a main category may be a parent, so the tree
                // never goes deeper than two levels
                $parentRow = $result->fetch_assoc();
                if ($parentRow["parent_id"] != NULL) {
                    $parentErr = "A sub-category cannot be used as a parent";
                    $parentId = "";
                }
            }
        }
    }

    // a category that already has sub-categories cannot become a sub-category
    if ($parentErr == "" && $parentId != "" && $editId > 0) {
        $row = $db->countChildCategories($conn, $editId)->fetch_assoc();
        if ($row["total"] > 0) {
            $parentErr = "This category has sub-categories of its own, so it must stay a main category";
        }
    }

    // ---- duplicate name under the same parent ----
    if ($nameErr == "" && $parentErr == "") {
        $row = $db->countCategoryByName($conn, $name, $parentId, $editId)->fetch_assoc();
        if ($row["total"] > 0) {
            $nameErr = "This name is already used under the same parent";
        }
    }

    // ---- everything is valid, write to the database ----
    if ($nameErr == "" && $parentErr == "") {

        // an empty string must go into the database as NULL
        $parentValue = NULL;
        if ($parentId != "") {
            $parentValue = $parentId;
        }

        if ($editId > 0) {
            $done = $db->updateCategory($conn, $editId, $name, $parentValue);
            $doneMsg = "Category \"" . $name . "\" was updated.";
        } else {
            $done = $db->insertCategory($conn, $name, $parentValue);
            $doneMsg = "Category \"" . $name . "\" was added.";
        }

        if ($done) {
            $conn->close();
            $_SESSION["msg"] = $doneMsg;
            header("Location: category.php");
            exit();
        } else {
            $nameErr = "The category could not be saved. Please try again.";
        }
    }
}

// ---------------- PARENT DROPDOWN ----------------

$parentRows = array();

$result = $db->getParentCategories($conn);
while ($row = $result->fetch_assoc()) {
    // a category can never be its own parent
    if ($row["id"] != $editId) {
        $parentRows[] = $row;
    }
}

$conn->close();

?>
