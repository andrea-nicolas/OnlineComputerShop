<?php

require_once __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    $csrfFailed = true;
    $_POST = array();
} else {
    $csrfFailed = false;
}

$reviews    = array();
$successMsg = "";
$formError  = "";

$isAdminPage = $isAdmin;

if (!$isAdminPage) {

    $formError = $isLoggedIn
        ? "This page is for administrators only."
        : "You have to log in as an administrator to open this page.";

} else {

    if (isset($_POST["delete_review"])) {

        $reviewId = 0;
        if (isset($_POST["review_id"]) && is_numeric($_POST["review_id"])) {
            $reviewId = (int) $_POST["review_id"];
        }

        if ($reviewId <= 0) {

            $formError = "No review was selected.";

        } else {

            $result = $db->getReviewById($conn, $reviewId);

            if ($result->num_rows < 1) {
                $formError = "That review does not exist any more.";
            } elseif ($db->deleteReview($conn, $reviewId)) {
                redirect(link_to("/views/admin_reviews.php?msg=deleted"));
            } else {
                $formError = "The review could not be deleted. Please try again.";
            }
        }
    }

    if (isset($_GET["msg"]) && $_GET["msg"] == "deleted") {
        $successMsg = "The review has been removed.";
    }

    $result = $db->getAllReviews($conn);

    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

if ($csrfFailed) {
    $formError = "Your session has expired. Please refresh the page and try again.";
}
