<?php

// This AJAX file checks whether an email already exists.
require_once '../config/database.php';
require_once '../models/user_model.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$email = '';

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
}

if ($email == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array(
        'success' => true,
        'available' => false,
        'message' => 'Please enter a valid email.'
    ));
    exit;
}

try {
    if (emailAlreadyExists($email)) {
        echo json_encode(array(
            'success' => true,
            'available' => false,
            'message' => 'Email already exists.'
        ));
    } else {
        echo json_encode(array(
            'success' => true,
            'available' => true,
            'message' => 'Email is available.'
        ));
    }
} catch (Exception $error) {
    http_response_code(500);

    echo json_encode(array(
        'success' => false,
        'available' => false,
        'message' => 'Could not check email.'
    ));
}
?>
