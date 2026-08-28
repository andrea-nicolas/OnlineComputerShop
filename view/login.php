<?php
include "../control/login_control.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Online Computer Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/mycss.css">
</head>
<body class="login-body">

<div class="login-box">

    <h1>Admin Login</h1>
    <p class="login-sub">Online Computer Shop</p>

    <?php if ($loginErr != "") { ?>
        <div class="alert-error"><?php echo $loginErr; ?></div>
    <?php } ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
          onsubmit="return loginvalidation()">

        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>">
        <span class="error"><?php echo $emailErr; ?></span>
        <span class="error" id="email-error"></span>

        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <span class="error"><?php echo $passwordErr; ?></span>
        <span class="error" id="password-error"></span>

        <input type="submit" name="login" value="Login" class="btn btn-primary btn-block">

    </form>

</div>

<script src="../js/myjs.js"></script>
</body>
</html>
