<?php
require_once 'config/database.php';

// Payment method functions
function getAllPaymentMethods() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM payment_methods WHERE is_active = TRUE ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPaymentMethodById($paymentMethodId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM payment_methods WHERE id = ?");
    $stmt->execute([$paymentMethodId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function validatePaymentMethod($paymentMethodId) {
    $paymentMethod = getPaymentMethodById($paymentMethodId);
    return $paymentMethod && $paymentMethod['is_active'];
}
?>
