<?php
require_once 'includes/auth.php';
require_once 'includes/products.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = getCurrentUser();

// Get product ID
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$productId) {
    header('Location: my-products.php');
    exit();
}

// Get product details to verify ownership
$product = getProductById($productId);
if (!$product || $product['seller_id'] != $user['id']) {
    header('Location: my-products.php');
    exit();
}

// Delete the product
if (deleteProduct($productId)) {
    header('Location: my-products.php?deleted=1');
} else {
    header('Location: my-products.php?error=1');
}
exit();
?>
