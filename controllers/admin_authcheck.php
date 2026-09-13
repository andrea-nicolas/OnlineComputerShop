<?php

// ADMIN GATE
// Every admin page includes this file first. If the visitor is not
// logged in, or is logged in but is not an admin, they are sent back
// to the login page and the rest of the page never runs.

session_start();

if (empty($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

?>
