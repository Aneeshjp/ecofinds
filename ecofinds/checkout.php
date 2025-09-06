<?php
require_once 'includes/auth.php';
require_once 'includes/cart.php';
require_once 'includes/addresses.php';
require_once 'includes/payments.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login to checkout']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'checkout') {
    $user = getCurrentUser();
    $cartItems = getCartItems($user['id']);
    $addressId = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
    $paymentMethodId = isset($_POST['payment_method_id']) ? intval($_POST['payment_method_id']) : 0;
    
    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit();
    }
    
    if (!$addressId) {
        echo json_encode(['success' => false, 'message' => 'Please select a delivery address']);
        exit();
    }
    
    if (!$paymentMethodId) {
        echo json_encode(['success' => false, 'message' => 'Please select a payment method']);
        exit();
    }
    
    // Verify address belongs to user
    $address = getAddressById($addressId, $user['id']);
    if (!$address) {
        echo json_encode(['success' => false, 'message' => 'Invalid delivery address']);
        exit();
    }
    
    // Verify payment method is valid
    if (!validatePaymentMethod($paymentMethodId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
        exit();
    }
    
    $pdo = getDBConnection();
    $successCount = 0;
    $errors = [];
    
    try {
        $pdo->beginTransaction();
        
        foreach ($cartItems as $item) {
            // Create purchase record
            $stmt = $pdo->prepare("INSERT INTO purchases (buyer_id, product_id, seller_id, purchase_price, delivery_address_id, payment_method_id) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$user['id'], $item['product_id'], $item['seller_id'], $item['price'], $addressId, $paymentMethodId])) {
                // Mark product as sold
                $stmt = $pdo->prepare("UPDATE products SET status = 'sold' WHERE id = ?");
                $stmt->execute([$item['product_id']]);
                $successCount++;
            } else {
                $errors[] = "Failed to purchase: " . $item['title'];
            }
        }
        
        // Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        
        $pdo->commit();
        
        if ($successCount > 0) {
            echo json_encode([
                'success' => true, 
                'message' => "Successfully purchased {$successCount} item(s)" . (count($errors) > 0 ? '. Some items failed to purchase.' : '')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to purchase any items']);
        }
        
    } catch (Exception $e) {
        $pdo->rollback();
        echo json_encode(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
