<?php
require_once 'includes/auth.php';
require_once 'includes/cart.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit();
    }
    
    $user = getCurrentUser();
    
    if (addToCart($user['id'], $productId)) {
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
