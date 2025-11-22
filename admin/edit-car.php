<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    header("Location: manage-cars.php");
    exit();
}

$carId = intval($_GET['id']);
$car = getCarById($carId);

if (!$car) {
    header("Location: manage-cars.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagePath = $car['image'];
    
    // Handle image upload
    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['car_image']['name'];
        $fileTmp = $_FILES['car_image']['tmp_name'];
        $fileSize = $_FILES['car_image']['size'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($fileExt, $allowed)) {
            if ($fileSize < 5000000) { // 5MB max
                // Delete old image if exists
                if (!empty($car['image']) && file_exists('../' . $car['image'])) {
                    unlink('../' . $car['image']);
                }
                
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
        
        if (updateCar($carId, $carData)) {
            $success = 'Car updated successfully!';
            $car = getCarById($carId);
        } else {
            $error = 'Failed to update car.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><i class="bi bi-car-front-fill"></i> CarRental Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="manage-cars.php">Manage Cars</a></li>
                    <li class="nav-item"><a class="nav-link" href="view-bookings.php">View Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage-users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4"><i class="bi bi-pencil-square"></i> Edit Car</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Car Name *</label>
                                    <input type="text" class="form-control" name="car_name" value="<?php echo htmlspecialchars($car['car_name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand *</label>
                                    <input type="text" class="form-control" name="brand" value="<?php echo htmlspecialchars($car['brand']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Model *</label>
                                    <input type="text" class="form-control" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Year *</label>
                                    <input type="number" class="form-control" name="year" value="<?php echo $car['year']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Color *</label>
                                    <input type="text" class="form-control" name="color" value="<?php echo htmlspecialchars($car['color']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Registration Number *</label>
                                    <input type="text" class="form-control" name="registration_number" value="<?php echo htmlspecialchars($car['registration_number']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Seat Capacity *</label>
                                    <input type="number" class="form-control" name="seat_capacity" value="<?php echo $car['seat_capacity']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price Per Day ($) *</label>
                                    <input type="number" class="form-control" name="price_per_day" step="0.01" value="<?php echo $car['price_per_day']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status *</label>
                                    <select class="form-select" name="status" required>
                                        <option value="available" <?php echo $car['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                        <option value="booked" <?php echo $car['status'] === 'booked' ? 'selected' : ''; ?>>Booked</option>
                                        <option value="maintenance" <?php echo $car['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fuel Type *</label>
                                    <select class="form-select" name="fuel_type" required>
                                        <option value="Petrol" <?php echo $car['fuel_type'] === 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                                        <option value="Diesel" <?php echo $car['fuel_type'] === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                                        <option value="Electric" <?php echo $car['fuel_type'] === 'Electric' ? 'selected' : ''; ?>>Electric</option>
                                        <option value="Hybrid" <?php echo $car['fuel_type'] === 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Transmission *</label>
                                    <select class="form-select" name="transmission" required>
                                        <option value="Automatic" <?php echo $car['transmission'] === 'Automatic' ? 'selected' : ''; ?>>Automatic</option>
                                        <option value="Manual" <?php echo $car['transmission'] === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Car Image</label>
                                <?php if (!empty($car['image']) && file_exists('../' . $car['image'])): ?>
                                    <div class="mb-2">
                                        <img src="../<?php echo htmlspecialchars($car['image']); ?>" alt="Current car image" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                        <p class="text-muted small mt-1">Current image</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="car_image" name="car_image" accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">Leave empty to keep current image. Allowed: JPG, JPEG, PNG, GIF. Max size: 5MB</small>
                                <div class="mt-2">
                                    <img id="image_preview" src="" alt="Preview" style="max-width: 300px; max-height: 200px; display: none;" class="img-thumbnail">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($car['description']); ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Car</button>
                                <a href="manage-cars.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
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
