<?php

require_once __DIR__ . "/bootstrap.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    echo json_encode(array(
        "ok"      => false,
        "message" => "Your session has expired. Please refresh the page and try again.",
        "reviews" => array(),
        "count"   => 0
    ));
    exit;
}

$action    = isset($_REQUEST["action"]) ? clean_input($_REQUEST["action"]) : "";
$productId = 0;

if (isset($_REQUEST["product_id"]) && is_numeric($_REQUEST["product_id"])) {
    $productId = (int) $_REQUEST["product_id"];
}

$response = array(
    "ok"      => false,
    "message" => "",
    "reviews" => array(),
    "count"   => 0
);

function review_list($db, $conn, $productId, $userId)
{
    $list   = array();
    $result = $db->getReviewsByProduct($conn, $productId);

    while ($row = $result->fetch_assoc()) {
        $list[] = array(
            "id"       => (int) $row["id"],
            "name"     => $row["reviewer_name"],
            "comment"  => $row["comment"],
            "date"     => show_date($row["created_at"]),
            "is_mine"  => ((int) $row["user_id"] == $userId)
        );
    }
    return $list;
}

if ($productId <= 0 && $action != "admin_delete") {

    $response["message"] = "No product was selected.";

} elseif ($action == "list") {

    $response["ok"]      = true;
    $response["reviews"] = review_list($db, $conn, $productId, $userId);
    $response["count"]   = count($response["reviews"]);

} elseif ($action == "add") {

    $comment = isset($_POST["comment"]) ? clean_input($_POST["comment"]) : "";

    if (!$isLoggedIn) {
        $response["message"] = "You have to sign in before posting a review.";
    } elseif (!$isCustomer) {
        $response["message"] = "Only customers can post reviews.";
    } elseif (empty($comment)) {
        $response["message"] = "Please write your review before posting.";
    } elseif (mb_strlen($comment) < 5) {
        $response["message"] = "Too short. Write at least 5 characters.";
    } elseif (mb_strlen($comment) > 500) {
        $response["message"] = "Too long. Keep it under 500 characters.";
    } else {

        $newId = $db->addReview($conn, $productId, $userId, $comment);

        if ($newId > 0) {
            $response["ok"]      = true;
            $response["message"] = "Your review has been posted.";
        } else {
            $response["message"] = "The review could not be saved. Please try again.";
        }
    }

    $response["reviews"] = review_list($db, $conn, $productId, $userId);
    $response["count"]   = count($response["reviews"]);

} elseif ($action == "delete") {

    $reviewId = 0;
    if (isset($_POST["review_id"]) && is_numeric($_POST["review_id"])) {
        $reviewId = (int) $_POST["review_id"];
    }

    if (!$isLoggedIn) {
        $response["message"] = "You have to sign in first.";
    } elseif ($reviewId <= 0) {
        $response["message"] = "That review does not exist.";
    } else {

        $result = $db->getReviewById($conn, $reviewId);

        if ($result->num_rows < 1) {
            $response["message"] = "That review does not exist any more.";
        } else {
            $review = $result->fetch_assoc();

            if ((int) $review["user_id"] != $userId) {
                $response["message"] = "You can only delete your own review.";
            } elseif ($db->deleteOwnReview($conn, $reviewId, $userId)) {
                $response["ok"]      = true;
                $response["message"] = "Your review has been deleted.";
            } else {
                $response["message"] = "The review could not be deleted.";
            }
        }
    }

    $response["reviews"] = review_list($db, $conn, $productId, $userId);
    $response["count"]   = count($response["reviews"]);

} elseif ($action == "admin_delete") {

    $reviewId = 0;
    if (isset($_POST["review_id"]) && is_numeric($_POST["review_id"])) {
        $reviewId = (int) $_POST["review_id"];
    }

    if (!$isAdmin) {
        $response["message"] = "This action is for administrators only.";
    } elseif ($reviewId <= 0) {
        $response["message"] = "No review was selected.";
    } else {

        $result = $db->getReviewById($conn, $reviewId);

        if ($result->num_rows < 1) {
            $response["message"] = "That review does not exist any more.";
        } elseif ($db->deleteReview($conn, $reviewId)) {
            $response["ok"]      = true;
            $response["message"] = "The review has been removed.";
        } else {
            $response["message"] = "The review could not be deleted.";
        }
    }

    $all    = array();
    $result = $db->getAllReviews($conn);

    while ($row = $result->fetch_assoc()) {
        $all[] = array("id" => (int) $row["id"]);
    }

    $response["count"] = count($all);

} else {

    $response["message"] = "Unknown action.";
}

echo json_encode($response);
