<?php


function getUserByEmail($email)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE email = ?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($email));

    return $statement->fetch();
}


function getUserById($id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($id));

    return $statement->fetch();
}


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


function addUser($name, $email, $passwordHash, $role, $createdAt)
{
    global $conn;

    $sql = "INSERT INTO users (name, email, password_hash, role, profile_picture, created_at)
            VALUES (?, ?, ?, ?, NULL, ?)";

    $statement = $conn->prepare($sql);
    return $statement->execute(array($name, $email, $passwordHash, $role, $createdAt));
}


function updateUserProfile($id, $name, $email, $profilePicture)
{
    global $conn;

    $sql = "UPDATE users
            SET name = ?, email = ?, profile_picture = ?
            WHERE id = ?";

    $statement = $conn->prepare($sql);
    return $statement->execute(array($name, $email, $profilePicture, $id));
}


function updateUserPassword($id, $passwordHash)
{
    global $conn;

    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $statement = $conn->prepare($sql);

    return $statement->execute(array($passwordHash, $id));
}
?>
