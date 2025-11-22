<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagePath = '';
    
    // Handle image upload
    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['car_image']['name'];
        $fileTmp = $_FILES['car_image']['tmp_name'];
        $fileSize = $_FILES['car_image']['size'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($fileExt, $allowed)) {
            if ($fileSize < 5000000) { // 5MB max
                $newFilename = uniqid('car_', true) . '.' . $fileExt;
                $uploadPath = '../uploads/cars/' . $newFilename;
                
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    $imagePath = 'uploads/cars/' . $newFilename;
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Image size must be less than 5MB.';
            }
        } else {
            $error = 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
        }
    }
    
    if (empty($error)) {
        $carData = [
            'car_name' => trim($_POST['car_name']),
            'brand' => trim($_POST['brand']),
            'model' => trim($_POST['model']),
            'year' => intval($_POST['year']),
            'color' => trim($_POST['color']),
            'registration_number' => trim($_POST['registration_number']),
            'seat_capacity' => intval($_POST['seat_capacity']),
            'price_per_day' => floatval($_POST['price_per_day']),
            'fuel_type' => $_POST['fuel_type'],
            'transmission' => $_POST['transmission'],
            'description' => trim($_POST['description']),
            'status' => $_POST['status'],
            'image' => $imagePath
        ];
        
        if (addCar($carData)) {
            $success = 'Car added successfully!';
        } else {
            $error = 'Failed to add car. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="bi bi-car-front-fill"></i> CarRental Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage-cars.php">Manage Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view-bookings.php">View Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">
                            <i class="bi bi-plus-circle"></i> Add New Car
                        </h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                                <a href="manage-cars.php" class="alert-link">View all cars</a>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="car_name" class="form-label">Car Name *</label>
                                    <input type="text" class="form-control" id="car_name" name="car_name" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="brand" class="form-label">Brand *</label>
                                    <input type="text" class="form-control" id="brand" name="brand" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="model" class="form-label">Model *</label>
                                    <input type="text" class="form-control" id="model" name="model" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="year" class="form-label">Year *</label>
                                    <input type="number" class="form-control" id="year" name="year" min="2000" max="2025" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="color" class="form-label">Color *</label>
                                    <input type="text" class="form-control" id="color" name="color" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="registration_number" class="form-label">Registration Number *</label>
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="seat_capacity" class="form-label">Seat Capacity *</label>
                                    <input type="number" class="form-control" id="seat_capacity" name="seat_capacity" min="2" max="15" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="price_per_day" class="form-label">Price Per Day ($) *</label>
                                    <input type="number" class="form-control" id="price_per_day" name="price_per_day" step="0.01" min="0" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="available">Available</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fuel_type" class="form-label">Fuel Type *</label>
                                    <select class="form-select" id="fuel_type" name="fuel_type" required>
                                        <option value="Petrol">Petrol</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="Electric">Electric</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="transmission" class="form-label">Transmission *</label>
                                    <select class="form-select" id="transmission" name="transmission" required>
                                        <option value="Automatic">Automatic</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="car_image" class="form-label">Car Image</label>
                                <input type="file" class="form-control" id="car_image" name="car_image" accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">Allowed: JPG, JPEG, PNG, GIF. Max size: 5MB</small>
                                <div class="mt-2">
                                    <img id="image_preview" src="" alt="Preview" style="max-width: 300px; max-height: 200px; display: none;" class="img-thumbnail">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Add Car
                                </button>
                                <a href="manage-cars.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
