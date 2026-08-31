<?php

// This AJAX file returns the latest 6 products as JSON.
require_once '../config/database.php';
require_once '../models/product_model.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    $products = getLatestSixProducts();
    $html = '';

    if (count($products) == 0) {
        $html = '<p>No products found.</p>';
    }

    foreach ($products as $product) {
        $name = htmlspecialchars($product['name']);
        $brand = htmlspecialchars($product['brand_name']);
        $category = htmlspecialchars($product['category_name']);
        $price = number_format($product['price'], 2);

        $html .= '<div class="product-card">';
        $html .= '<div class="product-image">';

        if (!empty($product['image_path'])) {
            $imagePath = htmlspecialchars($product['image_path']);
            $html .= '<img src="' . $imagePath . '" alt="' . $name . '">';
        } else {
            $html .= '<span>PC</span>';
        }

        $html .= '</div>';
        $html .= '<h3>' . $name . '</h3>';
        $html .= '<p>' . $brand . ' | ' . $category . '</p>';
        $html .= '<strong>৳' . $price . '</strong>';
        $html .= '</div>';
    }

    echo json_encode(array(
        'success' => true,
        'html' => $html,
        'message' => 'Products refreshed successfully.',
        'refreshed_at' => date('H:i:s')
    ));
} catch (Exception $error) {
    http_response_code(500);

    echo json_encode(array(
        'success' => false,
        'message' => 'Could not load products.'
    ));
}
?>
