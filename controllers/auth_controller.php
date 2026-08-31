<?php

// The database has no remember_token column.
// So Remember Me uses one signed cookie instead of changing the database.
define('REMEMBER_COOKIE_NAME', 'remember_user');
define('REMEMBER_SECRET', 'online-computer-shop-simple-secret-2026');

function registerController()
{
    // A logged-in user does not need the register page.
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    $pageTitle = 'Register';
    $categories = getAllMainCategories();
    $errors = array();

    $name = '';
    $email = '';
    $role = 'customer';

    // This code runs only after the Register button is clicked.
    if (isset($_POST['register_button'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $role = $_POST['role'];

        if ($name == '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($password != $confirmPassword) {
            $errors[] = 'Password and confirm password do not match.';
        }

        if ($role != 'admin' && $role != 'customer') {
            $errors[] = 'Please select a valid role.';
        }

        if ($email != '' && emailAlreadyExists($email)) {
            $errors[] = 'This email is already registered.';
        }

        // If there is no error, save the new user.
        if (count($errors) == 0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $createdAt = date('Y-m-d H:i:s');

            addUser($name, $email, $passwordHash, $role, $createdAt);

            $_SESSION['message'] = 'Registration successful. Please login.';
            header('Location: index.php?page=login');
            exit;
        }
    }

    require 'views/auth/register.php';
}

function loginController()
{
    // A logged-in user does not need the login page.
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    $pageTitle = 'Login';
    $categories = getAllMainCategories();
    $errors = array();
    $email = '';

    // This code runs only after the Login button is clicked.
    if (isset($_POST['login_button'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if ($email == '') {
            $errors[] = 'Email is required.';
        }

        if ($password == '') {
            $errors[] = 'Password is required.';
        }

        if (count($errors) == 0) {
            $user = getUserByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Save the important user information in the session.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // If Remember Me was checked, create the 30-day cookie.
                if (isset($_POST['remember_me'])) {
                    createRememberCookie($user);
                } else {
                    deleteRememberCookie();
                }

                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'Email or password is incorrect.';
            }
        }
    }

    require 'views/auth/login.php';
}

function logoutController()
{
    // Remove session login information.
    session_unset();
    session_destroy();

    // Also remove the Remember Me cookie.
    deleteRememberCookie();

    header('Location: index.php?page=login');
    exit;
}

function createRememberCookie($user)
{
    // Cookie will be valid for 30 days.
    $expiryTime = time() + (30 * 24 * 60 * 60);

    // Basic cookie text: user id + expiry time.
    $cookieData = $user['id'] . '|' . $expiryTime;

    // Signature stops a user from changing the cookie manually.
    $signature = hash_hmac(
        'sha256',
        $cookieData,
        REMEMBER_SECRET . $user['password_hash']
    );

    $cookieValue = $cookieData . '|' . $signature;

    setcookie(
        REMEMBER_COOKIE_NAME,
        $cookieValue,
        $expiryTime,
        '/',
        '',
        false,
        true
    );
}

function deleteRememberCookie()
{
    setcookie(REMEMBER_COOKIE_NAME, '', time() - 3600, '/');
}

function tryRememberLogin()
{
    // Stop if the user is already logged in.
    if (isset($_SESSION['user_id'])) {
        return;
    }

    // Stop if there is no Remember Me cookie.
    if (!isset($_COOKIE[REMEMBER_COOKIE_NAME])) {
        return;
    }

    $parts = explode('|', $_COOKIE[REMEMBER_COOKIE_NAME]);

    if (count($parts) != 3) {
        deleteRememberCookie();
        return;
    }

    $userId = (int) $parts[0];
    $expiryTime = (int) $parts[1];
    $savedSignature = $parts[2];

    if ($expiryTime < time()) {
        deleteRememberCookie();
        return;
    }

    $user = getUserById($userId);

    if (!$user) {
        deleteRememberCookie();
        return;
    }

    $cookieData = $userId . '|' . $expiryTime;

    $correctSignature = hash_hmac(
        'sha256',
        $cookieData,
        REMEMBER_SECRET . $user['password_hash']
    );

    // If the cookie is correct, create the login session again.
    if (hash_equals($correctSignature, $savedSignature)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    } else {
        deleteRememberCookie();
    }
}
?>
