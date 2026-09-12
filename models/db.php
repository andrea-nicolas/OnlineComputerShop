<?php

require_once __DIR__ . "/config.php";

mysqli_report(MYSQLI_REPORT_OFF);

class Task4Db{

function openConn()
{
    $servername = DB_HOST;
    $username   = DB_USER;
    $password   = DB_PASS;
    $dbname     = DB_NAME;

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("<h2>Connection failed: " . $conn->connect_error . "</h2>
             <p>Start MySQL in XAMPP and import the " . DB_NAME . " database.</p>");
    }

    $conn->set_charset("utf8mb4");
    return $conn;
}

function closeConn($conn)
{
    $conn->close();
}

function getUserById($conn, $id)
{
    $qry  = "SELECT id, name, email, role, profile_picture, created_at
             FROM users WHERE id = ?";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}

function getAllAccounts($conn)
{
    $qry = "SELECT id, name, email, role
            FROM users
            ORDER BY role DESC, id ASC";

    return $conn->query($qry);
}

function getAnyUser($conn)
{
    $qry = "SELECT id, name, email, role, profile_picture, created_at
            FROM users
            ORDER BY (role = 'customer') DESC, id ASC
            LIMIT 1";

    return $conn->query($qry);
}

function getAllProducts($conn)
{
    $qry = "SELECT p.id, p.name, p.price, p.image_path, p.stock, p.manufacturer_review,
                   c.name AS category_name,
                   b.name AS brand_name,
                   (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id) AS review_count
            FROM products p
            JOIN categories c ON c.id = p.category_id
            JOIN brands     b ON b.id = p.brand_id
            ORDER BY p.created_at DESC, p.id DESC";

    return $conn->query($qry);
}

function getProductById($conn, $id)
{
    $qry = "SELECT p.id, p.name, p.description, p.manufacturer_review,
                   p.price, p.image_path, p.stock, p.created_at,
                   c.name AS category_name,
                   b.name AS brand_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            JOIN brands     b ON b.id = p.brand_id
            WHERE p.id = ?";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}

function getReviewsByProduct($conn, $product_id)
{
    $qry = "SELECT r.id, r.comment, r.created_at, r.user_id,
                   u.name AS reviewer_name
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            WHERE r.product_id = ?
            ORDER BY r.created_at DESC, r.id DESC";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    return $stmt->get_result();
}

function addReview($conn, $product_id, $user_id, $comment)
{
    $qry  = "INSERT INTO reviews (product_id, user_id, comment, created_at)
             VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iis", $product_id, $user_id, $comment);
    $res  = $stmt->execute();

    if ($res) {
        return $conn->insert_id;
    }
    return 0;
}

function getReviewById($conn, $id)
{
    $qry  = "SELECT id, product_id, user_id, comment, created_at FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}

function deleteOwnReview($conn, $id, $user_id)
{
    $qry  = "DELETE FROM reviews WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

function getCartItems($conn, $user_id)
{
    $qry = "SELECT c.id AS cart_id, c.quantity,
                   p.id AS product_id, p.name, p.price, p.stock, p.image_path,
                   b.name AS brand_name
            FROM carts c
            JOIN products p ON p.id = c.product_id
            JOIN brands   b ON b.id = p.brand_id
            WHERE c.user_id = ?
            ORDER BY c.id ASC";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->get_result();
}

function countCartItems($conn, $user_id)
{
    $qry  = "SELECT COUNT(*) AS total FROM carts WHERE user_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();
    return (int) $row["total"];
}

function addToCart($conn, $user_id, $product_id, $quantity)
{
    $qry  = "SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row     = $res->fetch_assoc();
        $cart_id = (int) $row["id"];
        $newQty  = (int) $row["quantity"] + $quantity;

        $qry  = "UPDATE carts SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ii", $newQty, $cart_id);
    } else {

        $now  = time();
        $qry  = "INSERT INTO carts (user_id, product_id, quantity, added_at)
                 VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iiii", $user_id, $product_id, $quantity, $now);
    }

    return $stmt->execute();
}

function updateCartQuantity($conn, $cart_id, $user_id, $quantity)
{
    $qry  = "UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    $stmt->execute();

    return $stmt->affected_rows >= 0;
}

function removeCartItem($conn, $cart_id, $user_id)
{
    $qry  = "DELETE FROM carts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

function clearCart($conn, $user_id)
{
    $qry  = "DELETE FROM carts WHERE user_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->affected_rows;
}

function placeOrder($conn, $user_id, $items, $total_amount, $payment_method)
{
    $conn->begin_transaction();

    $qry  = "INSERT INTO orders (user_id, total_amount, payment_method, status, order_date)
             VALUES (?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ids", $user_id, $total_amount, $payment_method);
    $res  = $stmt->execute();

    $order_id = 0;
    if ($res) {
        $order_id = $conn->insert_id;
    }

    if ($order_id > 0) {
        $qry  = "INSERT INTO order_items (order_id, product_id, quantity, unit_price)
                 VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($qry);

        foreach ($items as $item) {
            $product_id = (int) $item["product_id"];
            $quantity   = (int) $item["quantity"];
            $unit_price = (float) $item["price"];

            $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $unit_price);
            $res = $stmt->execute();

            if (!$res) {
                break;
            }
        }
    } else {
        $res = false;
    }

    if ($res) {
        $qry  = "DELETE FROM carts WHERE user_id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $user_id);
        $res  = $stmt->execute();
    }

    if ($res) {
        $conn->commit();
    } else {
        $conn->rollback();
        $order_id = 0;
    }

    return $order_id;
}

function getOrderById($conn, $order_id)
{
    $qry = "SELECT o.id, o.user_id, o.total_amount, o.payment_method,
                   o.status, o.order_date,
                   u.name  AS customer_name,
                   u.email AS customer_email
            FROM orders o
            JOIN users u ON u.id = o.user_id
            WHERE o.id = ?";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    return $stmt->get_result();
}

function getOrderItems($conn, $order_id)
{
    $qry = "SELECT oi.id, oi.quantity, oi.unit_price, oi.product_id,
                   p.name AS product_name, p.image_path
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
            ORDER BY oi.id ASC";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    return $stmt->get_result();
}

function getOrdersByUser($conn, $user_id)
{
    $qry = "SELECT o.id, o.total_amount, o.payment_method, o.status, o.order_date,
                   (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
            FROM orders o
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC, o.id DESC";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->get_result();
}

function getAllCustomers($conn)
{
    $qry = "SELECT u.id, u.name, u.email, u.created_at,
                   (SELECT COUNT(*) FROM reviews r WHERE r.user_id = u.id) AS review_count,
                   (SELECT COUNT(*) FROM orders  o WHERE o.user_id = u.id) AS order_count
            FROM users u
            WHERE u.role = 'customer'
            ORDER BY u.created_at DESC, u.id DESC";

    return $conn->query($qry);
}

function countCustomers($conn)
{
    $qry = "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'";
    $res = $conn->query($qry);

    if ($res) {
        $row = $res->fetch_assoc();
        return (int) $row["total"];
    }
    return 0;
}

function countUserData($conn, $id)
{
    $qry = "SELECT (SELECT COUNT(*) FROM reviews r WHERE r.user_id = ?) AS reviews,
                   (SELECT COUNT(*) FROM carts   c WHERE c.user_id = ?) AS cart_items,
                   (SELECT COUNT(*) FROM orders  o WHERE o.user_id = ?) AS orders";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $id, $id, $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function deleteCustomer($conn, $id)
{
    $conn->begin_transaction();

    $qry  = "DELETE FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $id);
    $res  = $stmt->execute();

    if ($res) {
        $qry  = "DELETE FROM users WHERE id = ? AND role = 'customer'";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $res  = $stmt->execute();

        if ($res && $stmt->affected_rows < 1) {
            $res = false;
        }
    }

    if ($res) {
        $conn->commit();
    } else {
        $conn->rollback();
    }

    return $res;
}

function deleteReview($conn, $id)
{
    $qry  = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

function getAllReviews($conn)
{
    $qry = "SELECT r.id, r.comment, r.created_at, r.user_id, r.product_id,
                   u.name  AS reviewer_name,
                   u.email AS reviewer_email,
                   p.name  AS product_name
            FROM reviews r
            JOIN users    u ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            ORDER BY r.created_at DESC, r.id DESC";

    return $conn->query($qry);
}

function getRecentReviews($conn, $limit)
{
    $limit = (int) $limit;

    $qry = "SELECT r.id, r.comment, r.created_at,
                   u.name AS reviewer_name,
                   p.name AS product_name,
                   p.id   AS product_id
            FROM reviews r
            JOIN users    u ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            ORDER BY r.created_at DESC, r.id DESC
            LIMIT $limit";

    return $conn->query($qry);
}

function countAllReviews($conn)
{
    $qry = "SELECT COUNT(*) AS total FROM reviews";
    $res = $conn->query($qry);

    if ($res) {
        $row = $res->fetch_assoc();
        return (int) $row["total"];
    }
    return 0;
}

function getRecentOrders($conn, $limit)
{
    $limit = (int) $limit;

    $qry = "SELECT o.id, o.total_amount, o.payment_method, o.status, o.order_date,
                   u.id   AS user_id,
                   u.name AS customer_name,
                   (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
            FROM orders o
            JOIN users u ON u.id = o.user_id
            ORDER BY o.order_date DESC, o.id DESC
            LIMIT $limit";

    return $conn->query($qry);
}

function getOrderStats($conn)
{
    $qry = "SELECT COUNT(*) AS total_orders,
                   SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_orders,
                   COALESCE(SUM(total_amount), 0) AS total_value
            FROM orders";

    $res = $conn->query($qry);

    if ($res) {
        return $res->fetch_assoc();
    }
    return array("total_orders" => 0, "pending_orders" => 0, "total_value" => 0);
}

}
