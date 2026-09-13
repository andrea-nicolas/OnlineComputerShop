<?php

require_once __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    $csrfFailed = true;
    $_POST = array();
} else {
    $csrfFailed = false;
}

$accounts  = array();
$formError = "";

if ($isLoggedIn) {
    redirect(link_to("/views/products.php"));
}

if (isset($_POST["sign_in"])) {

    $pickedId = 0;
    if (isset($_POST["user_id"]) && is_numeric($_POST["user_id"])) {
        $pickedId = (int) $_POST["user_id"];
    }

    if ($pickedId <= 0) {
        $formError = "Please choose an account to sign in with.";
    } else {
        $result = $db->getUserById($conn, $pickedId);

        if ($result->num_rows < 1) {
            $formError = "That account does not exist any more.";
        } else {
            $user = $result->fetch_assoc();

            $_SESSION["user_id"] = (int) $user["id"];
            $_SESSION["name"]    = $user["name"];
            $_SESSION["role"]    = $user["role"];

            if (isset($_POST["remember"])) {
                setcookie(
                    REMEMBER_COOKIE,
                    make_remember_value((int) $user["id"]),
                    time() + (86400 * REMEMBER_DAYS),
                    "/"
                );
            }

            redirect(link_to("/views/products.php"));
        }
    }
}

$result = $db->getAllAccounts($conn);

while ($row = $result->fetch_assoc()) {
    $accounts[] = $row;
}

if ($csrfFailed) {
    $formError = "Your session has expired. Please refresh the page and try again.";
}
