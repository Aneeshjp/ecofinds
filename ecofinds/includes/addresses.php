<?php
require_once 'config/database.php';

// Address management functions
function getUserAddresses($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserDefaultAddress($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? AND is_default = TRUE LIMIT 1");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createAddress($userId, $fullName, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $phone, $isDefault = false) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // If this is set as default, remove default from other addresses
        if ($isDefault) {
            $stmt = $pdo->prepare("UPDATE addresses SET is_default = FALSE WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
        
        // Insert new address
        $stmt = $pdo->prepare("INSERT INTO addresses (user_id, full_name, address_line1, address_line2, city, state, postal_code, country, phone, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$userId, $fullName, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $phone, $isDefault]);
        
        $pdo->commit();
        return $result;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

function updateAddress($addressId, $userId, $fullName, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $phone, $isDefault = false) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // If this is set as default, remove default from other addresses
        if ($isDefault) {
            $stmt = $pdo->prepare("UPDATE addresses SET is_default = FALSE WHERE user_id = ? AND id != ?");
            $stmt->execute([$userId, $addressId]);
        }
        
        // Update address
        $stmt = $pdo->prepare("UPDATE addresses SET full_name = ?, address_line1 = ?, address_line2 = ?, city = ?, state = ?, postal_code = ?, country = ?, phone = ?, is_default = ? WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$fullName, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $phone, $isDefault, $addressId, $userId]);
        
        $pdo->commit();
        return $result;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

function deleteAddress($addressId, $userId) {
    $pdo = getDBConnection();
    
    // Check if this is the default address
    $stmt = $pdo->prepare("SELECT is_default FROM addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$address) {
        return false;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Delete the address
        $stmt = $pdo->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$addressId, $userId]);
        
        // If we deleted the default address, set another address as default
        if ($address['is_default'] && $result) {
            $stmt = $pdo->prepare("UPDATE addresses SET is_default = TRUE WHERE user_id = ? LIMIT 1");
            $stmt->execute([$userId]);
        }
        
        $pdo->commit();
        return $result;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

function getAddressById($addressId, $userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
