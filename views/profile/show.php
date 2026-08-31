<?php require 'views/layouts/header.php'; ?>

<h2>My Profile</h2>

<div class="profile-grid">

    <section class="panel">
        <h3>Profile Information</h3>

        <?php if ($user['profile_picture'] != '') { ?>
            <img class="profile-picture"
                 src="<?php echo htmlspecialchars($user['profile_picture']); ?>"
                 alt="Profile Picture">
        <?php } else { ?>
            <div class="profile-placeholder">No Photo</div>
        <?php } ?>

        <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>

        <?php if (count($profileErrors) > 0) { ?>
            <div class="error-box">
                <?php foreach ($profileErrors as $error) { ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <!--
            Clicking Update Profile sends this form to index.php?page=profile.
            profileController() sees update_profile_button and updates the user.
        -->
        <form action="index.php?page=profile" method="post" enctype="multipart/form-data">

            <label>Name</label>
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Profile Picture</label>
            <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png">
            <small>JPG, JPEG or PNG. Maximum size 2 MB.</small>

            <button class="button full" type="submit" name="update_profile_button">
                Update Profile
            </button>
        </form>
    </section>

    <section class="panel">
        <h3>Change Password</h3>

        <?php if (count($passwordErrors) > 0) { ?>
            <div class="error-box">
                <?php foreach ($passwordErrors as $error) { ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <!--
            Clicking Change Password sends this form to the same profile controller.
            The controller sees change_password_button and changes only the password.
        -->
        <form action="index.php?page=profile" method="post">

            <label>Current Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>

            <button class="button full" type="submit" name="change_password_button">
                Change Password
            </button>
        </form>
    </section>

</div>

<?php require 'views/layouts/footer.php'; ?>
