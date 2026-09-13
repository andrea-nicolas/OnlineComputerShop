<?php

// Saving a product's active/inactive status happens in two places: the AJAX
// endpoint, and the plain form post used when JavaScript is switched off.
// The rule is written once here so both behave in exactly the same way.
//
// A product has no row in product_status until its status is changed for the
// first time, so this inserts a row or updates the existing one.

function save_product_status($db, $conn, $productId, $status)
{
    $row = $db->countProductStatusRow($conn, $productId)->fetch_assoc();

    if ($row["total"] == 0) {
        return $db->insertProductStatus($conn, $productId, $status);
    }

    return $db->updateProductStatus($conn, $productId, $status);
}

// Only these two values are ever accepted, whatever was posted.
function is_valid_status($status)
{
    if ($status == "active" || $status == "inactive") {
        return true;
    }
    return false;
}
