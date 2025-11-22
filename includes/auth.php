<?php
require_once __DIR__ . '/../config/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Register new user
function registerUser($username, $email, $password, $full_name, $phone = '', $address = '') {
    $conn = getDBConnection();
    
    // Check if username or email already exists
    $checkQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Username or email already exists'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insertQuery = "INSERT INTO users (username, email, password, full_name, phone, address, role) 
                    VALUES (?, ?, ?, ?, ?, ?, 'customer')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $full_name, $phone, $address);
    
    if ($stmt->execute()) {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => true, 'message' => 'Registration successful'];
    } else {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Registration failed'];
    }
}

// Login user
function loginUser($username, $password) {
    $conn = getDBConnection();
    
    $query = "SELECT id, username, email, password, full_name, role FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            closeDBConnection($conn);
            return ['success' => true, 'role' => $user['role']];
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return ['success' => false, 'message' => 'Invalid username or password'];
}

// Logout user
function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

// Require admin
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header("Location: ../index.php");
        exit();
    }
}

// Get current user ID
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Get current user info
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'full_name' => $_SESSION['full_name'],
        'role' => $_SESSION['role']
    ];
}
?>
