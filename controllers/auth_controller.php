<?php

define('REMEMBER_COOKIE_NAME', 'remember_user');
define('REMEMBER_SECRET', 'online-computer-shop-simple-secret-2026');

function registerController()
{
    
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
    
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    $pageTitle = 'Login';
    $categories = getAllMainCategories();
    $errors = array();
    $email = '';

   
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
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

               
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
    
    session_unset();
    session_destroy();

    
    deleteRememberCookie();

    header('Location: index.php?page=login');
    exit;
}

function createRememberCookie($user)
{
    
    $expiryTime = time() + (30 * 24 * 60 * 60);

    
    $cookieData = $user['id'] . '|' . $expiryTime;

   
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
    
    if (isset($_SESSION['user_id'])) {
        return;
    }

    
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

    
    if (hash_equals($correctSignature, $savedSignature)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    } else {
        deleteRememberCookie();
    }
}
?>
