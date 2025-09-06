<?php
require_once 'includes/auth.php';
require_once 'includes/cart.php';
require_once 'includes/products.php';
require_once 'includes/addresses.php';
require_once 'includes/payments.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login to purchase']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $addressId = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
    $paymentMethodId = isset($_POST['payment_method_id']) ? intval($_POST['payment_method_id']) : 0;
    
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
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
    
    $user = getCurrentUser();
    $product = getProductById($productId);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }
    
    if ($product['seller_id'] == $user['id']) {
        echo json_encode(['success' => false, 'message' => 'Cannot purchase your own product']);
        exit();
    }
    
    if ($product['status'] != 'active') {
        echo json_encode(['success' => false, 'message' => 'Product is no longer available']);
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
    
    if (createPurchaseWithAddressAndPayment($user['id'], $productId, $product['seller_id'], $product['price'], $addressId, $paymentMethodId)) {
        echo json_encode(['success' => true, 'message' => 'Purchase successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to complete purchase']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
