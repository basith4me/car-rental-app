<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Get all available cars
$cars = getAllCars('available');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Cars - Car Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-car-front-fill"></i> CarRental
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cars.php">Browse Cars</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">Dashboard</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="customer/my-bookings.php">My Bookings</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-light py-4">
        <div class="container">
            <h1 class="mb-0">Browse Available Cars</h1>
            <p class="text-muted mb-0">Find the perfect car for your journey</p>
        </div>
    </div>

    <!-- Cars Section -->
    <section class="py-5">
        <div class="container">
            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input type="text" class="form-control" id="searchInput" 
                                           placeholder="Search by car name, brand, or model..." 
                                           onkeyup="searchTable('searchInput', 'carsContainer')">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <select class="form-select" id="filter_transmission" onchange="filterCars()">
                                        <option value="all">All Transmission</option>
                                        <option value="Automatic">Automatic</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <select class="form-select" id="filter_fuel" onchange="filterCars()">
                                        <option value="all">All Fuel Types</option>
                                        <option value="Petrol">Petrol</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="Electric">Electric</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <button class="btn btn-secondary w-100" onclick="location.reload()">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cars Grid -->
            <div class="row" id="carsContainer">
                <?php if (empty($cars)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No cars available at the moment.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cars as $car): ?>
                        <div class="col-md-4 mb-4 car-card" 
                             data-brand="<?php echo htmlspecialchars($car['brand']); ?>"
                             data-transmission="<?php echo htmlspecialchars($car['transmission']); ?>"
                             data-fuel="<?php echo htmlspecialchars($car['fuel_type']); ?>">
                            <div class="card h-100">
                                <?php if (!empty($car['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($car['image']); ?>"
                                         alt="<?php echo htmlspecialchars($car['car_name']); ?>"
                                         class="card-img-top car-card-img"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'img-placeholder car-card-img\'><i class=\'bi bi-car-front\'></i></div>';">
                                <?php else: ?>
                                    <div class="img-placeholder car-card-img">
                                        <i class="bi bi-car-front"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($car['car_name']); ?></h5>
                                    <p class="text-muted mb-2"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'); ?></p>
                                    <div class="mb-3">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($car['transmission']); ?></span>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($car['fuel_type']); ?></span>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($car['seat_capacity']); ?> Seats</span>
                                        <span class="badge bg-success status-<?php echo $car['status']; ?>">
                                            <?php echo ucfirst($car['status']); ?>
                                        </span>
                                    </div>
                                    <p class="card-text text-truncate">
                                        <?php echo htmlspecialchars(substr($car['description'], 0, 100)); ?>...
                                    </p>
                                    <h4 class="text-primary mb-3">$<?php echo number_format($car['price_per_day'], 2); ?><small class="text-muted">/day</small></h4>
                                    <a href="car-details.php?id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2024 CarRental System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
