<?php
session_start();
require_once "../Registration/controllers/RegisterController.php";
$controller = new RegisterController();
$controller->register();
?>
