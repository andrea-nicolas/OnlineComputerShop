<?php
require_once __DIR__ . "/../control/login_control.php";
$pageTitle = "Sign in";
include __DIR__ . "/partials/header.php";
?>

<div class="page-head">
    <div>
        <h1>Sign in</h1>
        <p class="muted">Signing in starts a PHP session. Tick Remember me and a cookie keeps you signed in for <?php echo REMEMBER_DAYS; ?> days.</p>
    </div>
</div>

<?php if (isset($_GET["msg"]) && $_GET["msg"] == "out") { ?>
    <div class="alert alert-success">You have been signed out. The session and the cookie were both cleared.</div>
<?php } ?>

<?php if (isset($_GET["msg"]) && $_GET["msg"] == "required") { ?>
    <div class="alert alert-warn">You have to be signed in as a customer to open that page.</div>
<?php } ?>

<?php if ($formError !== "") { ?>
    <div class="alert alert-error"><?php echo e($formError); ?></div>
<?php } ?>

<div class="card">

    <?php if (count($accounts) === 0) { ?>

        <div class="empty">
            No customer accounts in the database. Import <code>sql/reset_demo.sql</code> first.
        </div>

    <?php } else { ?>

        <p class="small muted">
            The registration and password form is Task 1. Until that is merged, pick one of the
            seeded accounts below. The admin account opens the dashboard and the management pages.
        </p>

        <form action="" method="post" onsubmit="return validateSignIn()">
                <?php echo csrf_field(); ?>

            <div class="pay-options">
                <?php foreach ($accounts as $a) { ?>
                    <div class="pay-option" id="acc-card-<?php echo (int) $a["id"]; ?>">
                        <input type="radio"
                               id="acc-<?php echo (int) $a["id"]; ?>"
                               name="user_id"
                               value="<?php echo (int) $a["id"]; ?>">
                        <label for="acc-<?php echo (int) $a["id"]; ?>">
                            <span class="pay-name"><?php echo e($a["name"]); ?></span>
                            <span class="pay-note">
                                <?php echo e($a["email"]); ?>
                                &middot; <?php echo e($a["role"]); ?>
                            </span>
                        </label>
                    </div>
                <?php } ?>
            </div>

            <span class="error-text" id="user_id-error"></span>

            <p style="margin-top:14px;">
                <label style="font-weight:400;">
                    <input type="checkbox" name="remember" value="1" style="width:auto;">
                    Remember me for <?php echo REMEMBER_DAYS; ?> days
                </label>
            </p>

            <button type="submit" name="sign_in" class="btn">Sign in</button>
        </form>

    <?php } ?>
</div>

<script src="<?php echo BASE_URL; ?>/js/login_validation.js"></script>

<?php include __DIR__ . "/partials/footer.php"; ?>
