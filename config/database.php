<?php


$host = 'localhost';
$databaseName = 'onlinecomputershop';
$username = 'root';
$password = '';

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$databaseName;charset=utf8mb4",
        $username,
        $password
    );

    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    die('Database connection failed: ' . $error->getMessage());
}
?>
