<?php

require_once __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !csrf_ok()) {
    $csrfFailed = true;
    $_POST = array();
} else {
    $csrfFailed = false;
}

$productId = 0;
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $productId = (int) $_GET["id"];
}

$product = null;
if ($productId > 0) {
    $result = $db->getProductById($conn, $productId);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}

$reviews      = array();
$commentValue = "";
$commentError = "";
$formError    = "";
$successMsg   = "";
$cartMsg      = "";

if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "added") {
        $successMsg = "Your review has been posted.";
    } elseif ($_GET["msg"] == "deleted") {
        $successMsg = "Your review has been deleted.";
    } elseif ($_GET["msg"] == "cart") {
        $cartMsg = "Added to your cart.";
    }
}

if ($product != null) {

    if (isset($_POST["add_to_cart"])) {

        if (!$isLoggedIn) {
            $formError = "You have to log in before adding to the cart.";
        } elseif (!$isCustomer) {
            $formError = "Only customers can add products to the cart.";
        } elseif ((int) $product["stock"] < 1) {
            $formError = "This product is out of stock.";
        } elseif ($db->addToCart($conn, $userId, $productId, 1)) {
            redirect(link_to("/views/product_details.php?id=" . $productId . "&msg=cart"));
        } else {
            $formError = "The product could not be added to the cart.";
        }
    }

    if (isset($_POST["post_review"])) {

        $commentValue = isset($_POST["comment"]) ? clean_input($_POST["comment"]) : "";

        if (!$isLoggedIn) {
            $formError = "You have to log in before posting a review.";
        } elseif (!$isCustomer) {
            $formError = "Only customers can post reviews.";
        }

        if ($formError == "") {
            if (empty($commentValue)) {
                $commentError = "Please write your review before posting.";
            } elseif (mb_strlen($commentValue) < 5) {
                $commentError = "Too short. Write at least 5 characters.";
            } elseif (mb_strlen($commentValue) > 500) {
                $commentError = "Too long. Keep it under 500 characters.";
            }
        }

        if ($formError == "" && $commentError == "") {
            $newId = $db->addReview($conn, $productId, $userId, $commentValue);

            if ($newId > 0) {
                redirect(link_to("/views/product_details.php?id=" . $productId . "&msg=added") . "#reviews");
            } else {
                $formError = "The review could not be saved. Please try again.";
            }
        }
    }

    if (isset($_POST["delete_review"])) {

        $reviewId = 0;
        if (isset($_POST["review_id"]) && is_numeric($_POST["review_id"])) {
            $reviewId = (int) $_POST["review_id"];
        }

        if (!$isLoggedIn) {
            $formError = "You have to log in first.";
        } elseif ($reviewId <= 0) {
            $formError = "That review does not exist.";
        } else {
            $result = $db->getReviewById($conn, $reviewId);

            if ($result->num_rows < 1) {
                $formError = "That review does not exist any more.";
            } else {
                $review = $result->fetch_assoc();

                if ((int) $review["user_id"] != $userId) {
                    $formError = "You can only delete your own review.";
                } elseif ($db->deleteOwnReview($conn, $reviewId, $userId)) {
                    redirect(link_to("/views/product_details.php?id=" . $productId . "&msg=deleted") . "#reviews");
                } else {
                    $formError = "The review could not be deleted. Please try again.";
                }
            }
        }
    }

    $result = $db->getReviewsByProduct($conn, $productId);

    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

if ($csrfFailed) {
    $formError = "Your session has expired. Please refresh the page and try again.";
}
