<?php require 'views/layouts/header.php'; ?>

<div class="form-box">
    <h2>Login</h2>

    <?php if (count($errors) > 0) { ?>
        <div class="error-box">
            <?php foreach ($errors as $error) { ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>
        </div>
    <?php } ?>

   
    <form action="index.php?page=login" method="post">

        <label>Email</label>
        <input type="email" name="email"
               value="<?php echo htmlspecialchars($email); ?>" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label class="checkbox-row">
            <input type="checkbox" name="remember_me">
            Remember Me for 30 days
        </label>

       
        <button class="button full" type="submit" name="login_button">Login</button>
    </form>

    <p class="form-bottom">
        New user?
        <a href="index.php?page=register">Create an account</a>
    </p>
</div>

<?php require 'views/layouts/footer.php'; ?>
