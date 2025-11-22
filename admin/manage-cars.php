<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete'])) {
    $carId = intval($_GET['delete']);
    if (deleteCar($carId)) {
        $success = 'Car deleted successfully!';
    } else {
        $error = 'Failed to delete car.';
    }
}

$cars = getAllCars();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cars - Admin</title>
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

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Cars</h1>
            <a href="add-car.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Car
            </a>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="mb-3">
                <input type="text" class="form-control" id="searchInput" 
                       placeholder="Search cars..." 
                       onkeyup="searchTable('searchInput', 'carsTable')">
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="carsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Car Name</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Registration</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cars)): ?>
                            <tr>
                                <td colspan="9" class="text-center">No cars found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cars as $car): ?>
                                <tr>
                                    <td><?php echo $car['id']; ?></td>
                                    <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                                    <td><?php echo htmlspecialchars($car['brand']); ?></td>
                                    <td><?php echo htmlspecialchars($car['model']); ?></td>
                                    <td><?php echo $car['year']; ?></td>
                                    <td><?php echo htmlspecialchars($car['registration_number']); ?></td>
                                    <td>$<?php echo number_format($car['price_per_day'], 2); ?></td>
                                    <td>
                                        <span class="badge status-<?php echo $car['status']; ?>">
                                            <?php echo ucfirst($car['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit-car.php?id=<?php echo $car['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="?delete=<?php echo $car['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this car?')" 
                                           title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
