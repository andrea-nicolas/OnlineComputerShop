<?php

require_once __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    $csrfFailed = true;
    $_POST = array();
} else {
    $csrfFailed = false;
}

$customers  = array();
$successMsg = "";
$formError  = "";

$isAdminPage = $isAdmin;

if (!$isAdminPage) {

    $formError = $isLoggedIn
        ? "This page is for administrators only."
        : "You have to log in as an administrator to open this page.";

} else {

    if (isset($_POST["delete_customer"])) {

        $customerId = 0;
        if (isset($_POST["customer_id"]) && is_numeric($_POST["customer_id"])) {
            $customerId = (int) $_POST["customer_id"];
        }

        if ($customerId <= 0) {

            $formError = "No customer was selected.";

        } elseif ($customerId == $userId) {

            $formError = "You cannot delete your own account.";

        } else {

            $result = $db->getUserById($conn, $customerId);

            if ($result->num_rows < 1) {
                $formError = "That customer does not exist.";
            } else {
                $customer = $result->fetch_assoc();

                if ($customer["role"] != "customer") {

                    $formError = "Only customer accounts can be removed here.";

                } else {

                    $counts = $db->countUserData($conn, $customerId);

                    if ($db->deleteCustomer($conn, $customerId)) {

                        redirect(link_to("/view/admin_customers.php?msg=deleted"
                            . "&r=" . (int) $counts["reviews"]
                            . "&o=" . (int) $counts["orders"]
                            . "&c=" . (int) $counts["cart_items"]));
                    } else {
                        $formError = "That customer could not be removed. Nothing was deleted.";
                    }
                }
            }
        }
    }

    if (isset($_GET["msg"]) && $_GET["msg"] == "deleted") {
        $r = isset($_GET["r"]) ? (int) $_GET["r"] : 0;
        $o = isset($_GET["o"]) ? (int) $_GET["o"] : 0;
        $c = isset($_GET["c"]) ? (int) $_GET["c"] : 0;

        $successMsg = "Customer removed, along with "
                    . $r . " review"    . ($r == 1 ? "" : "s") . ", "
                    . $o . " order"     . ($o == 1 ? "" : "s") . " and "
                    . $c . " cart item" . ($c == 1 ? "" : "s") . ".";
    }

    $result = $db->getAllCustomers($conn);

    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

if ($csrfFailed) {
    $formError = "Your session has expired. Please refresh the page and try again.";
}
