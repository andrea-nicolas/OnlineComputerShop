<?php

session_start();

require_once __DIR__ . "/../models/config.php";
require_once __DIR__ . "/../models/db.php";

$db   = new Task4Db();
$conn = $db->openConn();

$currentUser = null;

if (isset($_SESSION["user_id"])) {

    $result = $db->getUserById($conn, (int) $_SESSION["user_id"]);

    if ($result->num_rows > 0) {
        $currentUser = $result->fetch_assoc();
    } else {

        session_unset();
    }
}

if ($currentUser == null) {

    $rememberedId = read_remember_cookie();

    if ($rememberedId > 0) {
        $result = $db->getUserById($conn, $rememberedId);

        if ($result->num_rows > 0) {
            $currentUser = $result->fetch_assoc();

            $_SESSION["user_id"] = (int) $currentUser["id"];
            $_SESSION["name"]    = $currentUser["name"];
            $_SESSION["role"]    = $currentUser["role"];
        }
    }
}

$isLoggedIn = ($currentUser != null);
$userId     = $isLoggedIn ? (int) $currentUser["id"] : 0;
$userName   = $isLoggedIn ? $currentUser["name"]     : "Guest";
$userRole   = $isLoggedIn ? $currentUser["role"]     : "guest";
$isAdmin    = ($userRole == "admin");
$isCustomer = ($userRole == "customer");
