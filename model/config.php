<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "onlinecomputershop");

define("BASE_URL", "/LabTask02");

define("REMEMBER_COOKIE", "ocs_remember");
define("REMEMBER_DAYS", 30);
define("COOKIE_SECRET", "labtask02-group02-task4");

function e($value)
{
    if ($value === null) {
        $value = "";
    }
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

function money($amount)
{
    return "BDT " . number_format((float) $amount, 2);
}

function show_date($datetime)
{
    if (empty($datetime)) {
        return "";
    }
    return date("d M Y, h:i A", strtotime($datetime));
}

function redirect($url)
{
    header("Location: " . $url);
    exit;
}

function link_to($path)
{
    return BASE_URL . $path;
}

function make_remember_value($userId)
{
    return $userId . "|" . hash_hmac("sha256", $userId, COOKIE_SECRET);
}

function read_remember_cookie()
{
    if (!isset($_COOKIE[REMEMBER_COOKIE])) {
        return 0;
    }

    $parts = explode("|", $_COOKIE[REMEMBER_COOKIE]);

    if (count($parts) != 2) {
        return 0;
    }

    $userId    = (int) $parts[0];
    $signature = $parts[1];

    if ($userId > 0 && hash_equals(hash_hmac("sha256", $userId, COOKIE_SECRET), $signature)) {
        return $userId;
    }
    return 0;
}

function csrf_token()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_ok()
{
    if (!isset($_POST["csrf_token"])) {
        return false;
    }
    return hash_equals(csrf_token(), $_POST["csrf_token"]);
}

function payment_methods()
{
    return array(
        "cash"  => "Cash on Delivery",
        "card"  => "Credit / Debit Card",
        "bkash" => "bKash (online wallet)",
    );
}

function payment_label($value)
{
    $list = payment_methods();
    return isset($list[$value]) ? $list[$value] : $value;
}
