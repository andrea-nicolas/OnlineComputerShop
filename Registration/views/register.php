<?php

$name = $name ?? '';
$email = $email ?? '';
$role = $role ?? '';
$errors = $errors ?? [];
$successMessage = $successMessage ?? '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-box">

    <div id="successMessage" style="display: none;">
        <?php echo $successMessage; ?>
    </div>

    <h2>Computer Shop - Register Account</h2>

    <form id="registerForm" method="POST" action="index.php" onsubmit="return validateForm()">

        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <p id="nameError" class="error"><?php if (isset($errors['name'])) echo $errors['name']; ?></p>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <p id="emailError" class="error"><?php if (isset($errors['email'])) echo $errors['email']; ?></p>

        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <p id="passwordError" class="error"><?php if (isset($errors['password'])) echo $errors['password']; ?></p>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <p id="confirmPasswordError" class="error"><?php if (isset($errors['confirm_password'])) echo $errors['confirm_password']; ?></p>

        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="">Select a role</option>
            <option value="customer" <?php if ($role == 'customer') echo 'selected'; ?>>Customer</option>
            <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
        </select>
        <p id="roleError" class="error"><?php if (isset($errors['role'])) echo $errors['role']; ?></p>

        <button type="submit">Register</button>

    </form>

</div>

<script src="javascript/validation.js"></script>

</body>
</html>
