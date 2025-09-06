<?php
require_once 'config/database.php';

// Product management functions
function getAllCategories() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createProduct($title, $description, $price, $categoryId, $sellerId, $imagePath = 'placeholder.svg') {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO products (title, description, price, category_id, seller_id, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$title, $description, $price, $categoryId, $sellerId, $imagePath]);
}

function getUserProducts($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          WHERE p.seller_id = ? 
                          ORDER BY p.created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($productId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name, u.username as seller_name 
                          FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          JOIN users u ON p.seller_id = u.id 
                          WHERE p.id = ?");
    $stmt->execute([$productId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateProduct($productId, $title, $description, $price, $categoryId, $imagePath = null) {
    $pdo = getDBConnection();
    
    if ($imagePath) {
        $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, category_id = ?, image_path = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $price, $categoryId, $imagePath, $productId]);
    } else {
        $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $price, $categoryId, $productId]);
    }
}

function deleteProduct($productId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$productId]);
}

function getAllProducts($limit = null, $offset = 0, $categoryId = null, $search = null) {
    $pdo = getDBConnection();
    
    $sql = "SELECT p.*, c.name as category_name, u.username as seller_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            JOIN users u ON p.seller_id = u.id 
            WHERE p.status = 'active'";
    
    $params = [];
    
    if ($categoryId) {
        $sql .= " AND p.category_id = ?";
        $params[] = $categoryId;
    }
    
    if ($search) {
        $sql .= " AND (p.title LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductsCount($categoryId = null, $search = null) {
    $pdo = getDBConnection();
    
    $sql = "SELECT COUNT(*) as count FROM products WHERE status = 'active'";
    $params = [];
    
    if ($categoryId) {
        $sql .= " AND category_id = ?";
        $params[] = $categoryId;
    }
    
    if ($search) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

// Image upload function
function uploadImage($file) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    }
    
    return false;
}
?>
