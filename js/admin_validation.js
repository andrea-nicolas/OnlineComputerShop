function adminCsrfToken() {
    return $("meta[name='csrf-token']").attr("content");
}

function confirmDeleteCustomer(name, reviews, orders) {
    var message = "Delete the customer \"" + name + "\"?\n\n"
                + "This also removes " + reviews + " review(s), "
                + orders + " order(s) and everything left in their cart.\n\n"
                + "This cannot be undone.";
    return confirm(message);
}

$(document).ready(function () {

    $("#admin-review-list").on("submit", "form.admin-delete-review", function (event) {

        event.preventDefault();

        var form = $(this);
        var author = form.attr("data-author");

        if (!confirm("Delete the review written by " + author + "? This cannot be undone.")) {
            return false;
        }

        $.post(
            "../controllers/api_reviews.php",
            {
                action: "admin_delete",
                review_id: form.find("input[name='review_id']").val(),
                csrf_token: adminCsrfToken()
            },
            function (data) {
                var box = $("#admin-review-message");
                box.removeClass("alert alert-success alert-error")
                   .addClass(data.ok ? "alert alert-success" : "alert alert-error")
                   .text(data.message).show();

                if (data.ok) {
                    form.closest("tr").remove();
                    $("#admin-review-count").text(data.count);
                }
            },
            "json"
        );

        return false;
    });
});
