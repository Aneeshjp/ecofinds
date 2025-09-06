<?php
require_once 'config/database.php';

// User authentication functions
function registerUser($username, $email, $password) {
    $pdo = getDBConnection();
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    
    if ($stmt->rowCount() > 0) {
        return false; // User already exists
    }
    
    // Hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$username, $email, $hashedPassword])) {
        return true;
    }
    return false;
}

function loginUser($email, $password) {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        return true;
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email']
        ];
    }
    return null;
}

function logoutUser() {
    session_destroy();
    header('Location: index.php');
    exit();
}

function updateUserProfile($userId, $username) {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
    return $stmt->execute([$username, $userId]);
}
?>
