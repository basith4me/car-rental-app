<?php
require_once __DIR__ . '/../config/database.php';

// Get all cars with optional filters
function getAllCars($status = null, $brand = null) {
    $conn = getDBConnection();
    
    $query = "SELECT * FROM cars WHERE 1=1";
    $params = [];
    $types = "";
    
    if ($status) {
        $query .= " AND status = ?";
        $params[] = $status;
        $types .= "s";
    }
    
    if ($brand) {
        $query .= " AND brand = ?";
        $params[] = $brand;
        $types .= "s";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    if (!empty($params)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($query);
    }
    
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    
    closeDBConnection($conn);
    return $cars;
}

// Get car by ID
function getCarById($carId) {
    $conn = getDBConnection();
    
    $query = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $car = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    return $car;
}

// Add new car
function addCar($carData) {
    $conn = getDBConnection();
    
    $query = "INSERT INTO cars (car_name, brand, model, year, color, registration_number, 
              seat_capacity, price_per_day, fuel_type, transmission, description, image, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssississssss", 
        $carData['car_name'],
        $carData['brand'],
        $carData['model'],
        $carData['year'],
        $carData['color'],
        $carData['registration_number'],
        $carData['seat_capacity'],
        $carData['price_per_day'],
        $carData['fuel_type'],
        $carData['transmission'],
        $carData['description'],
        $carData['image'],
        $carData['status']
    );
    
    $success = $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    
    return $success;
}

// Update car
function updateCar($carId, $carData) {
    $conn = getDBConnection();
    
    $query = "UPDATE cars SET car_name = ?, brand = ?, model = ?, year = ?, color = ?, 
              registration_number = ?, seat_capacity = ?, price_per_day = ?, fuel_type = ?, 
              transmission = ?, description = ?, status = ?";
    
    $params = [
        $carData['car_name'],
        $carData['brand'],
        $carData['model'],
        $carData['year'],
        $carData['color'],
        $carData['registration_number'],
        $carData['seat_capacity'],
        $carData['price_per_day'],
        $carData['fuel_type'],
        $carData['transmission'],
        $carData['description'],
        $carData['status']
    ];
    
    $types = "sssississsss";
    
    if (!empty($carData['image'])) {
        $query .= ", image = ?";
        $params[] = $carData['image'];
        $types .= "s";
    }
    
    $query .= " WHERE id = ?";
    $params[] = $carId;
    $types .= "i";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    $success = $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    
    return $success;
}

// Delete car
function deleteCar($carId) {
    $conn = getDBConnection();
    
    $query = "DELETE FROM cars WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $carId);
    
    $success = $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    
    return $success;
}

// Create booking
function createBooking($userId, $carId, $startDate, $endDate, $totalDays, $totalPrice) {
    $conn = getDBConnection();
    
    // Check if car is available for the dates
    $checkQuery = "SELECT id FROM bookings WHERE car_id = ? AND status != 'cancelled' 
                   AND ((start_date <= ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?))";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("issss", $carId, $startDate, $startDate, $endDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Car is not available for selected dates'];
    }
    
    // Create booking
    $insertQuery = "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_days, total_price, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'confirmed')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iissid", $userId, $carId, $startDate, $endDate, $totalDays, $totalPrice);
    
    if ($stmt->execute()) {
        $bookingId = $conn->insert_id;
        
        // Update car status
        $updateQuery = "UPDATE cars SET status = 'booked' WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $carId);
        $updateStmt->execute();
        $updateStmt->close();
        
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => true, 'booking_id' => $bookingId];
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return ['success' => false, 'message' => 'Failed to create booking'];
}

// Get booking by ID
function getBookingById($bookingId) {
    $conn = getDBConnection();
    
    $query = "SELECT b.*, c.car_name, c.brand, c.model, c.registration_number, c.image,
              u.full_name, u.email, u.phone 
              FROM bookings b 
              JOIN cars c ON b.car_id = c.id 
              JOIN users u ON b.user_id = u.id 
              WHERE b.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $booking = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    return $booking;
}

// Get user bookings
function getUserBookings($userId) {
    $conn = getDBConnection();
    
    $query = "SELECT b.*, c.car_name, c.brand, c.model, c.image 
              FROM bookings b 
              JOIN cars c ON b.car_id = c.id 
              WHERE b.user_id = ? 
              ORDER BY b.booking_date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return $bookings;
}

// Get all bookings (for admin)
function getAllBookings() {
    $conn = getDBConnection();
    
    $query = "SELECT b.*, c.car_name, c.brand, c.registration_number, u.full_name, u.email 
              FROM bookings b 
              JOIN cars c ON b.car_id = c.id 
              JOIN users u ON b.user_id = u.id 
              ORDER BY b.booking_date DESC";
    
    $result = $conn->query($query);
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    closeDBConnection($conn);
    return $bookings;
}

// Cancel booking
function cancelBooking($bookingId, $userId = null) {
    $conn = getDBConnection();
    
    // Get booking details
    $query = "SELECT car_id, user_id, status FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    
    if (!$booking) {
        $stmt->close();
        closeDBConnection($conn);
        return false;
    }
    
    // Check if user is authorized
    if ($userId && $booking['user_id'] != $userId) {
        $stmt->close();
        closeDBConnection($conn);
        return false;
    }
    
    // Update booking status
    $updateQuery = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $bookingId);
    $success = $stmt->execute();
    
    if ($success) {
        // Update car status to available
        $carQuery = "UPDATE cars SET status = 'available' WHERE id = ?";
        $carStmt = $conn->prepare($carQuery);
        $carStmt->bind_param("i", $booking['car_id']);
        $carStmt->execute();
        $carStmt->close();
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return $success;
}

// Get dashboard statistics
function getDashboardStats() {
    $conn = getDBConnection();
    
    $stats = [];
    
    // Total cars
    $result = $conn->query("SELECT COUNT(*) as total FROM cars");
    $stats['total_cars'] = $result->fetch_assoc()['total'];
    
    // Available cars
    $result = $conn->query("SELECT COUNT(*) as total FROM cars WHERE status = 'available'");
    $stats['available_cars'] = $result->fetch_assoc()['total'];
    
    // Total bookings
    $result = $conn->query("SELECT COUNT(*) as total FROM bookings");
    $stats['total_bookings'] = $result->fetch_assoc()['total'];
    
    // Active bookings
    $result = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'confirmed'");
    $stats['active_bookings'] = $result->fetch_assoc()['total'];
    
    // Total users
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
    $stats['total_users'] = $result->fetch_assoc()['total'];
    
    // Total revenue
    $result = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE status != 'cancelled'");
    $stats['total_revenue'] = $result->fetch_assoc()['total'] ?: 0;
    
    closeDBConnection($conn);
    return $stats;
}

// Get all users (for admin)
function getAllUsers() {
    $conn = getDBConnection();
    
    $query = "SELECT id, username, email, full_name, phone, role, created_at 
              FROM users WHERE role = 'customer' ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    closeDBConnection($conn);
    return $users;
}

// Delete user (for admin)
function deleteUser($userId) {
    $conn = getDBConnection();
    
    $query = "DELETE FROM users WHERE id = ? AND role = 'customer'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    
    $success = $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    
    return $success;
}

// Get user by ID
function getUserById($userId) {
    $conn = getDBConnection();
    
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    return $user;
}

// Update user
function updateUser($userId, $userData) {
    $conn = getDBConnection();
    
    $query = "UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", 
        $userData['full_name'],
        $userData['email'],
        $userData['phone'],
        $userData['address'],
        $userId
    );
    
    $success = $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    
    return $success;
}
?>
