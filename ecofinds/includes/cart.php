<?php
require_once 'config/database.php';

// Cart management functions
function addToCart($userId, $productId, $quantity = 1) {
    $pdo = getDBConnection();
    
    // Check if product exists and is active
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ? AND status = 'active'");
    $stmt->execute([$productId]);
    if (!$stmt->fetch()) {
        return false;
    }
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingItem) {
        // Update quantity
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        return $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Add new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $productId, $quantity]);
    }
}

function getCartItems($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT c.*, p.title, p.description, p.price, p.image_path, p.seller_id, u.username as seller_name 
                          FROM cart c 
                          JOIN products p ON c.product_id = p.id 
                          JOIN users u ON p.seller_id = u.id 
                          WHERE c.user_id = ? AND p.status = 'active'
                          ORDER BY c.created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateCartQuantity($userId, $productId, $quantity) {
    $pdo = getDBConnection();
    
    if ($quantity <= 0) {
        return removeFromCart($userId, $productId);
    }
    
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$quantity, $userId, $productId]);
}

function removeFromCart($userId, $productId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$userId, $productId]);
}

function clearCart($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    return $stmt->execute([$userId]);
}

function getCartTotal($userId) {
    $items = getCartItems($userId);
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function getCartCount($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

// Purchase functions
function createPurchase($buyerId, $productId, $sellerId, $price) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // Create purchase record
        $stmt = $pdo->prepare("INSERT INTO purchases (buyer_id, product_id, seller_id, purchase_price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$buyerId, $productId, $sellerId, $price]);
        
        // Mark product as sold
        $stmt = $pdo->prepare("UPDATE products SET status = 'sold' WHERE id = ?");
        $stmt->execute([$productId]);
        
        // Remove from cart if exists
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$buyerId, $productId]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

function createPurchaseWithAddress($buyerId, $productId, $sellerId, $price, $addressId) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // Create purchase record
        $stmt = $pdo->prepare("INSERT INTO purchases (buyer_id, product_id, seller_id, purchase_price, delivery_address_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$buyerId, $productId, $sellerId, $price, $addressId]);
        
        // Mark product as sold
        $stmt = $pdo->prepare("UPDATE products SET status = 'sold' WHERE id = ?");
        $stmt->execute([$productId]);
        
        // Remove from cart if exists
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$buyerId, $productId]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

function createPurchaseWithAddressAndPayment($buyerId, $productId, $sellerId, $price, $addressId, $paymentMethodId) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // Create purchase record
        $stmt = $pdo->prepare("INSERT INTO purchases (buyer_id, product_id, seller_id, purchase_price, delivery_address_id, payment_method_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$buyerId, $productId, $sellerId, $price, $addressId, $paymentMethodId]);
        
        // Mark product as sold
        $stmt = $pdo->prepare("UPDATE products SET status = 'sold' WHERE id = ?");
        $stmt->execute([$productId]);
        
        // Remove from cart if exists
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$buyerId, $productId]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

function getUserPurchases($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT p.*, pr.title, pr.description, pr.image_path, u.username as seller_name, 
                          a.full_name, a.address_line1, a.city, a.state, a.postal_code, a.country,
                          pm.name as payment_method_name, pm.icon as payment_method_icon
                          FROM purchases p 
                          JOIN products pr ON p.product_id = pr.id 
                          JOIN users u ON p.seller_id = u.id 
                          JOIN addresses a ON p.delivery_address_id = a.id
                          JOIN payment_methods pm ON p.payment_method_id = pm.id
                          WHERE p.buyer_id = ? 
                          ORDER BY p.purchase_date DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
