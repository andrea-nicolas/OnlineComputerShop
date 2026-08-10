function validateForm()
{
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var role = document.getElementById('role').value;
    var hasError = false;

    clearErrors();

    if (name == '') {
        document.getElementById('nameError').innerHTML = 'Please enter your name.';
        hasError = true;
    }

    if (email == '') {
        document.getElementById('emailError').innerHTML = 'Please enter your email.';
        hasError = true;
    } else if (!isValidEmail(email)) {
        document.getElementById('emailError').innerHTML = 'Please enter a valid email.';
        hasError = true;
    }

    if (password == '') {
        document.getElementById('passwordError').innerHTML = 'Please enter a password.';
        hasError = true;
    } else if (password.length < 8) {
        document.getElementById('passwordError').innerHTML = 'Password must be at least 8 characters.';
        hasError = true;
    }

    if (confirmPassword == '') {
        document.getElementById('confirmPasswordError').innerHTML = 'Please confirm your password.';
        hasError = true;
    } else if (password != confirmPassword) {
        document.getElementById('confirmPasswordError').innerHTML = 'Passwords do not match.';
        hasError = true;
    }

    if (role == '') {
        document.getElementById('roleError').innerHTML = 'Please select a role.';
        hasError = true;
    }

    if (hasError == true) {
        return false;
    }

    return true;
}

function isValidEmail(email)
{
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

function clearErrors()
{
    document.getElementById('nameError').innerHTML = '';
    document.getElementById('emailError').innerHTML = '';
    document.getElementById('passwordError').innerHTML = '';
    document.getElementById('confirmPasswordError').innerHTML = '';
    document.getElementById('roleError').innerHTML = '';
}
window.onload = function()
{
    var form = document.getElementById('registerForm');
    var successElement = document.getElementById('successMessage');

    if (successElement) {
        var successMessage = successElement.innerHTML.trim();

        if (successMessage != '') {
            alert(successMessage);
            form.reset();
        }
    }
};
