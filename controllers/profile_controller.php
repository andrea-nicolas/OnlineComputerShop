<?php

function profileController()
{
    // Profile page is only for logged-in users.
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = 'Please login first.';
        header('Location: index.php?page=login');
        exit;
    }

    $pageTitle = 'My Profile';
    $categories = getAllMainCategories();
    $profileErrors = array();
    $passwordErrors = array();

    $user = getUserById($_SESSION['user_id']);

    // --------------------------------------------------
    // UPDATE PROFILE BUTTON
    // This section runs only when Update Profile is clicked.
    // --------------------------------------------------
    if (isset($_POST['update_profile_button'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $profilePicture = $user['profile_picture'];

        if ($name == '') {
            $profileErrors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $profileErrors[] = 'Please enter a valid email.';
        }

        if ($email != '' && emailAlreadyExists($email, $user['id'])) {
            $profileErrors[] = 'This email is already used by another user.';
        }

        // If the user selected a new profile picture, check and upload it.
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != '') {
            $fileName = $_FILES['profile_picture']['name'];
            $fileSize = $_FILES['profile_picture']['size'];
            $temporaryFile = $_FILES['profile_picture']['tmp_name'];
            $uploadError = $_FILES['profile_picture']['error'];

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = array('jpg', 'jpeg', 'png');

            if ($uploadError != 0) {
                $profileErrors[] = 'Image upload failed.';
            } elseif ($fileSize > 2 * 1024 * 1024) {
                $profileErrors[] = 'Image size must be 2 MB or smaller.';
            } elseif (!in_array($extension, $allowedExtensions)) {
                $profileErrors[] = 'Only JPG, JPEG and PNG images are allowed.';
            } else {
                $imageInfo = getimagesize($temporaryFile);

                if ($imageInfo === false) {
                    $profileErrors[] = 'The selected file is not a valid image.';
                } elseif ($imageInfo['mime'] != 'image/jpeg' && $imageInfo['mime'] != 'image/png') {
                    $profileErrors[] = 'Only JPG, JPEG and PNG images are allowed.';
                }
            }

            if (count($profileErrors) == 0) {
                $newFileName = 'profile_' . $user['id'] . '_' . time() . '.' . $extension;
                $savePath = 'public/uploads/profiles/' . $newFileName;

                if (move_uploaded_file($temporaryFile, $savePath)) {
                    $profilePicture = $savePath;
                } else {
                    $profileErrors[] = 'Could not save the image.';
                }
            }
        }

        if (count($profileErrors) == 0) {
            updateUserProfile($user['id'], $name, $email, $profilePicture);

            // Update the name shown in the navigation bar.
            $_SESSION['name'] = $name;

            $_SESSION['message'] = 'Profile updated successfully.';
            header('Location: index.php?page=profile');
            exit;
        }
    }

    // --------------------------------------------------
    // CHANGE PASSWORD BUTTON
    // This section runs only when Change Password is clicked.
    // --------------------------------------------------
    if (isset($_POST['change_password_button'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $user['password_hash'])) {
            $passwordErrors[] = 'Current password is incorrect.';
        }

        if (strlen($newPassword) < 8) {
            $passwordErrors[] = 'New password must be at least 8 characters.';
        }

        if ($newPassword != $confirmPassword) {
            $passwordErrors[] = 'New password and confirm password do not match.';
        }

        if (count($passwordErrors) == 0) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            updateUserPassword($user['id'], $newPasswordHash);

            // Old Remember Me cookie becomes unnecessary after password change.
            deleteRememberCookie();

            $_SESSION['message'] = 'Password changed successfully.';
            header('Location: index.php?page=profile');
            exit;
        }
    }

    // Read fresh user information before showing the page.
    $user = getUserById($_SESSION['user_id']);

    require 'views/profile/show.php';
}
?>
