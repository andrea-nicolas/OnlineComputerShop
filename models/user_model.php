<?php

// Find one user by email.
function getUserByEmail($email)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE email = ?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($email));

    return $statement->fetch();
}

// Find one user by id.
function getUserById($id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($id));

    return $statement->fetch();
}

// Check whether an email is already used.
// $ignoreId is useful when the current user updates the profile.
function emailAlreadyExists($email, $ignoreId = 0)
{
    global $conn;

    if ($ignoreId > 0) {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $statement = $conn->prepare($sql);
        $statement->execute(array($email, $ignoreId));
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $statement = $conn->prepare($sql);
        $statement->execute(array($email));
    }

    $user = $statement->fetch();

    if ($user) {
        return true;
    }

    return false;
}

// Insert a new user.
function addUser($name, $email, $passwordHash, $role, $createdAt)
{
    global $conn;

    $sql = "INSERT INTO users (name, email, password_hash, role, profile_picture, created_at)
            VALUES (?, ?, ?, ?, NULL, ?)";

    $statement = $conn->prepare($sql);
    return $statement->execute(array($name, $email, $passwordHash, $role, $createdAt));
}

// Update name, email and profile picture.
function updateUserProfile($id, $name, $email, $profilePicture)
{
    global $conn;

    $sql = "UPDATE users
            SET name = ?, email = ?, profile_picture = ?
            WHERE id = ?";

    $statement = $conn->prepare($sql);
    return $statement->execute(array($name, $email, $profilePicture, $id));
}

// Update only the password.
function updateUserPassword($id, $passwordHash)
{
    global $conn;

    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $statement = $conn->prepare($sql);

    return $statement->execute(array($passwordHash, $id));
}
?>
