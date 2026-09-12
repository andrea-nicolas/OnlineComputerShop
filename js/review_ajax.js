

var COMMENT_MIN = 5;
var COMMENT_MAX = 500;

function csrfToken() {
    return $("meta[name='csrf-token']").attr("content");
}

$(document).ready(function () {

    var productId = $("#reviews").data("product");

    if (!productId) {
        return;
    }

    function drawReviews(reviews) {

        var box = $("#review-list");
        box.empty();

        if (reviews.length === 0) {
            box.append(
                $("<div>").addClass("empty").text("No reviews yet. Be the first to write one.")
            );
            return;
        }

        $.each(reviews, function (i, r) {

            var head = $("<div>").addClass("review-head");

            head.append($("<span>").addClass("review-author").text(r.name));

            if (r.is_mine) {
                head.append($("<span>").addClass("badge badge-customer").text("you"));
            }

            head.append($("<span>").addClass("review-date").text(r.date));

            if (r.is_mine) {
                head.append(
                    $("<button>")
                        .addClass("btn btn-danger btn-small delete-review")
                        .css("margin-left", "auto")
                        .attr("data-id", r.id)
                        .text("Delete")
                );
            }

            var body = $("<p>").addClass("review-body").text(r.comment);

            box.append(
                $("<div>").addClass("review" + (r.is_mine ? " mine" : "")).append(head, body)
            );
        });
    }

    function setCount(n) {
        $("#review-count").text(n);
    }

    function showMessage(text, isError) {
        var box = $("#review-message");
        box.removeClass("alert-success alert-error").empty();

        if (text === "") {
            box.hide();
            return;
        }
        box.addClass(isError ? "alert alert-error" : "alert alert-success").text(text).show();
    }

    $("#review-form").submit(function (event) {

        event.preventDefault();

        var comment = $.trim($("#comment").val());

        $("#comment-error").text("");

        if (comment === "") {
            $("#comment-error").text("Please write your review before posting.");
            return false;
        }
        if (comment.length < COMMENT_MIN) {
            $("#comment-error").text("Too short. Write at least " + COMMENT_MIN + " characters.");
            return false;
        }
        if (comment.length > COMMENT_MAX) {
            $("#comment-error").text("Too long. Keep it under " + COMMENT_MAX + " characters.");
            return false;
        }

        $.post(
            "../control/api_reviews.php",
            { action: "add", product_id: productId, comment: comment, csrf_token: csrfToken() },
            function (data) {
                if (data.ok) {
                    $("#comment").val("");
                    updateCharCount();
                }
                showMessage(data.message, !data.ok);
                drawReviews(data.reviews);
                setCount(data.count);
            },
            "json"
        );

        return false;
    });

    $("#review-list").on("click", ".delete-review", function () {

        if (!confirm("Delete this review? This cannot be undone.")) {
            return;
        }

        $.post(
            "../control/api_reviews.php",
            { action: "delete", product_id: productId, review_id: $(this).attr("data-id"), csrf_token: csrfToken() },
            function (data) {
                showMessage(data.message, !data.ok);
                drawReviews(data.reviews);
                setCount(data.count);
            },
            "json"
        );
    });

    function updateCharCount() {
        var used = $.trim($("#comment").val()).length;
        $("#char-count").text(used + " / " + COMMENT_MAX);
        $("#char-count").css("color", used > COMMENT_MAX ? "#b32020" : "");
    }

    $("#comment").on("keyup change", updateCharCount);
    updateCharCount();
});
