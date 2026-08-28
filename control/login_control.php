<?php

include_once "../model/db.php";
include_once "../control/validation.php";

session_start();

// error messages and sticky values
$emailErr = "";
$passwordErr = "";
$loginErr = "";
$email = "";

// if an admin is already logged in, skip the login form
if (!empty($_SESSION["user_id"]) && $_SESSION["role"] == "admin") {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ---- email ----
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // ---- password ----
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    }

    // ---- everything valid, now check the database ----
    if ($emailErr == "" && $passwordErr == "") {

        $db = new mydb();
        $conn = $db->openConn();
        $result = $db->findUserByEmail($conn, $email);

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            if (password_verify($_POST["password"], $row["password_hash"])) {

                if ($row["role"] == "admin") {
                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["name"] = $row["name"];
                    $_SESSION["role"] = $row["role"];
                    $conn->close();
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $loginErr = "This panel is for admin accounts only";
                }

            } else {
                $loginErr = "Email or password is wrong";
            }

        } else {
            $loginErr = "Email or password is wrong";
        }

        $conn->close();
    }
}

?>
