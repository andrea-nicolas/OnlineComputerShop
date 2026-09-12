<?php

require_once __DIR__ . "/bootstrap.php";

$orders    = array();
$formError = "";

if (!$isLoggedIn) {

    redirect(link_to("/views/login.php?msg=required"));

} elseif (!$isCustomer) {

    $formError = "Only customers have an order history. Admins use the dashboard.";

} else {

    $result = $db->getOrdersByUser($conn, $userId);

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
