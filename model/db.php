<?php

// All database work for the admin panel is done by this class.
// Queries that take user input use prepared statements (prepare + bind_param).
// Queries with no user input use $conn->query() directly.

class mydb
{

    // ================= CONNECTION =================

    function openConn()
    {
        $conn = new mysqli("localhost", "root", "", "onlinecomputershop");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }


    // ================= USERS (login only) =================

    function findUserByEmail($conn, $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ================= DASHBOARD =================

    function countProducts($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM products";
        return $conn->query($sql);
    }

    function countCategories($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM categories";
        return $conn->query($sql);
    }

    function countBrands($conn)
    {
        $sql = "SELECT COUNT(*) AS total FROM brands";
        return $conn->query($sql);
    }

    function getLowStockProducts($conn, $limit)
    {
        $sql = "SELECT id, name, stock FROM products WHERE stock < ? ORDER BY stock ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ================= CATEGORIES =================

    // Every category with its parent name. A sub-category is listed
    // directly under its parent.
    function getAllCategories($conn)
    {
        $sql = "SELECT c.id, c.name, c.parent_id, c.created_at, p.name AS parent_name
                FROM categories c
                LEFT JOIN categories p ON c.parent_id = p.id
                ORDER BY IFNULL(p.name, c.name) ASC, c.parent_id IS NULL DESC, c.name ASC";
        return $conn->query($sql);
    }

    // Only main categories - used to fill the "parent category" dropdown.
    function getParentCategories($conn)
    {
        $sql = "SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY name ASC";
        return $conn->query($sql);
    }

    function getCategoryById($conn, $id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Stops the same name being used twice under the same parent.
    // $excludeId is the row being edited, so a row never clashes with itself.
    function countCategoryByName($conn, $name, $parentId, $excludeId)
    {
        if ($parentId == "") {
            $sql = "SELECT COUNT(*) AS total FROM categories
                    WHERE name = ? AND parent_id IS NULL AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $name, $excludeId);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM categories
                    WHERE name = ? AND parent_id = ? AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $name, $parentId, $excludeId);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    function insertCategory($conn, $name, $parentId)
    {
        $sql = "INSERT INTO categories (name, parent_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $parentId);
        return $stmt->execute();
    }

    function updateCategory($conn, $id, $name, $parentId)
    {
        $sql = "UPDATE categories SET name = ?, parent_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $name, $parentId, $id);
        return $stmt->execute();
    }

    function deleteCategory($conn, $id)
    {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    function countChildCategories($conn, $id)
    {
        $sql = "SELECT COUNT(*) AS total FROM categories WHERE parent_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function countProductsInCategory($conn, $id)
    {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function countBrandsInCategory($conn, $id)
    {
        $sql = "SELECT COUNT(*) AS total FROM brands WHERE category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ================= BRANDS =================

    function getAllBrands($conn)
    {
        $sql = "SELECT b.id, b.name, b.category_id, b.created_at,
                       c.name AS category_name, p.name AS parent_name
                FROM brands b
                JOIN categories c ON b.category_id = c.id
                LEFT JOIN categories p ON c.parent_id = p.id
                ORDER BY c.name ASC, b.name ASC";
        return $conn->query($sql);
    }

    function getBrandById($conn, $id)
    {
        $sql = "SELECT * FROM brands WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function countBrandByName($conn, $name, $categoryId, $excludeId)
    {
        $sql = "SELECT COUNT(*) AS total FROM brands
                WHERE name = ? AND category_id = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $name, $categoryId, $excludeId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function insertBrand($conn, $name, $categoryId)
    {
        $sql = "INSERT INTO brands (name, category_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $categoryId);
        return $stmt->execute();
    }

    function updateBrand($conn, $id, $name, $categoryId)
    {
        $sql = "UPDATE brands SET name = ?, category_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $name, $categoryId, $id);
        return $stmt->execute();
    }

    function deleteBrand($conn, $id)
    {
        $sql = "DELETE FROM brands WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    function countProductsInBrand($conn, $id)
    {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE brand_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Brands that belong to one category - fills the brand dropdown
    // on the product form.
    function getBrandsByCategory($conn, $categoryId)
    {
        $sql = "SELECT id, name FROM brands WHERE category_id = ? ORDER BY name ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ================= PRODUCTS =================

    function getAllProducts($conn)
    {
        $sql = "SELECT pr.id, pr.name, pr.price, pr.stock, pr.image_path, pr.created_at,
                       c.name AS category_name, b.name AS brand_name
                FROM products pr
                JOIN categories c ON pr.category_id = c.id
                JOIN brands b ON pr.brand_id = b.id
                ORDER BY pr.name ASC";
        return $conn->query($sql);
    }

    function getProductById($conn, $id)
    {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function countProductByName($conn, $name, $excludeId)
    {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE name = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $excludeId);
        $stmt->execute();
        return $stmt->get_result();
    }

    function insertProduct($conn, $name, $description, $review, $price,
                           $categoryId, $brandId, $imagePath, $stock)
    {
        $sql = "INSERT INTO products
                (name, description, manufacturer_review, price, category_id,
                 brand_id, image_path, stock, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdiisi", $name, $description, $review, $price,
                          $categoryId, $brandId, $imagePath, $stock);
        return $stmt->execute();
    }

    function updateProduct($conn, $id, $name, $description, $review, $price,
                           $categoryId, $brandId, $imagePath, $stock)
    {
        $sql = "UPDATE products SET name = ?, description = ?, manufacturer_review = ?,
                price = ?, category_id = ?, brand_id = ?, image_path = ?, stock = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdiisii", $name, $description, $review, $price,
                          $categoryId, $brandId, $imagePath, $stock, $id);
        return $stmt->execute();
    }

    function deleteProduct($conn, $id)
    {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}

?>
