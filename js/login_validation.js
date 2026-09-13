

$(document).ready(function () {

    $("input[name='user_id']").click(function () {
        $(".pay-option").removeClass("selected");
        $("#acc-card-" + $(this).val()).addClass("selected");
        $("#user_id-error").html("");
    });
});

function validateSignIn() {
    if ($("input[name='user_id']:checked").length === 0) {
        $("#user_id-error").html("Please choose an account to sign in with.");
        return false;
    }
    return true;
}
