<?php

class RegisterController
{
    public function register()
    {
        $name = '';
        $email = '';
        $role = '';
        $errors = [];
        $successMessage = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            $role = isset($_POST['role']) ? $_POST['role'] : '';

            if ($name == '') {
                $errors['name'] = 'Please enter your name.';
            }

            if ($email == '') {
                $errors['email'] = 'Please enter your email.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email.';
            }

            if ($password == '') {
                $errors['password'] = 'Please enter a password.';
            } elseif (strlen($password) < 8) {
                $errors['password'] = 'Password must be at least 8 characters.';
            }

            if ($confirmPassword == '') {
                $errors['confirm_password'] = 'Please confirm your password.';
            } elseif ($password != $confirmPassword) {
                $errors['confirm_password'] = 'Passwords do not match.';
            }

            if ($role == '') {
                $errors['role'] = 'Please select a role.';
            }

            if (empty($errors)) {
                $successMessage = 'Registration successful!';
                $name = '';
                $email = '';
                $role = '';
            }
        }
        include 'views/register.php';
    }
}

?>
