<?php

require_once __DIR__ . "/bootstrap.php";

$customerCount = 0;
$reviewCount   = 0;
$orderStats    = array("total_orders" => 0, "pending_orders" => 0, "total_value" => 0);
$recentOrders  = array();
$recentReviews = array();
$formError     = "";

$isAdminPage = $isAdmin;

if (!$isAdminPage) {

    $formError = $isLoggedIn
        ? "This page is for administrators only."
        : "You have to log in as an administrator to open this page.";

} else {

    $customerCount = $db->countCustomers($conn);
    $reviewCount   = $db->countAllReviews($conn);
    $orderStats    = $db->getOrderStats($conn);

    $result = $db->getRecentOrders($conn, 6);
    while ($row = $result->fetch_assoc()) {
        $recentOrders[] = $row;
    }

    $result = $db->getRecentReviews($conn, 5);
    while ($row = $result->fetch_assoc()) {
        $recentReviews[] = $row;
    }
}
