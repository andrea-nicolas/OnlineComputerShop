<?php

// ADMIN GATE FOR AJAX REQUESTS
//
// authcheck.php sends the visitor to the login page with a redirect. That is
// no use to an AJAX call, because JavaScript would receive the whole login
// page instead of data. This version answers with JSON and status 403 so the
// JavaScript can show a proper message.

session_start();

if (empty($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {

    header("Content-Type: application/json");
    http_response_code(403);

    $response = array();
    $response["success"] = false;
    $response["message"] = "Not allowed. Please log in as an admin again.";
    $response["brands"] = array();

    echo json_encode($response);
    exit();
}
