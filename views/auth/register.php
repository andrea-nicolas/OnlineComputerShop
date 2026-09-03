<?php require 'views/layouts/header.php'; ?>

<div class="form-box">
    <h2>Create Account</h2>

    
    <?php if (count($errors) > 0) { ?>
        <div class="error-box">
            <?php foreach ($errors as $error) { ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>
        </div>
    <?php } ?>

   
    <form action="index.php?page=register" method="post" onsubmit="return validateRegisterForm();">

        <label>Name</label>
        <input type="text" id="name" name="name"
               value="<?php echo htmlspecialchars($name); ?>" required>

        <label>Email</label>
        <input type="email" id="email" name="email"
               value="<?php echo htmlspecialchars($email); ?>"
               onblur="checkEmail();" required>
        <small id="emailMessage"></small>

        <label>Password</label>
        <input type="password" id="password" name="password" required>
        <small>Password must be at least 8 characters.</small>

        <label>Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label>Role</label>
        <select name="role">
            <option value="customer" <?php if ($role == 'customer') { echo 'selected'; } ?>>Customer</option>
            <option value="admin" <?php if ($role == 'admin') { echo 'selected'; } ?>>Admin</option>
        </select>

        
        <button class="button full" type="submit" name="register_button">Register</button>
    </form>

    <p class="form-bottom">
        Already have an account?
        <a href="index.php?page=login">Login</a>
    </p>
</div>

<?php require 'views/layouts/footer.php'; ?>
