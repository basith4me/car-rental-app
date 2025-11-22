<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Get available cars
$cars = getAllCars('available');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental System - Home</title>
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cars.php">Browse Cars</a>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to CarRental</h1>
            <p>Find your perfect ride for any occasion</p>
            <a href="cars.php" class="btn btn-light btn-lg">Browse Our Fleet</a>
        </div>
    </section>

    <!-- Featured Cars -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Featured Available Cars</h2>
            <div class="row">
                <?php if (empty($cars)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No cars available at the moment.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($cars, 0, 6) as $car): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if ($car['image'] && file_exists($car['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($car['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($car['car_name']); ?>" 
                                         class="card-img-top car-card-img">
                                <?php else: ?>
                                    <div class="img-placeholder car-card-img">
                                        <i class="bi bi-car-front"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($car['car_name']); ?></h5>
                                    <p class="text-muted"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></p>
                                    <div class="mb-2">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($car['transmission']); ?></span>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($car['fuel_type']); ?></span>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($car['seat_capacity']); ?> Seats</span>
                                    </div>
                                    <h4 class="text-primary">$<?php echo number_format($car['price_per_day'], 2); ?>/day</h4>
                                    <a href="car-details.php?id=<?php echo $car['id']; ?>" class="btn btn-primary w-100 mt-2">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (count($cars) > 6): ?>
                <div class="text-center mt-4">
                    <a href="cars.php" class="btn btn-primary btn-lg">View All Cars</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Us?</h2>
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <i class="bi bi-car-front display-4 text-primary mb-3"></i>
                    <h5>Wide Selection</h5>
                    <p>Choose from a diverse fleet of vehicles</p>
                </div>
                <div class="col-md-3 text-center mb-4">
                    <i class="bi bi-cash-coin display-4 text-primary mb-3"></i>
                    <h5>Best Prices</h5>
                    <p>Competitive rates with no hidden fees</p>
                </div>
                <div class="col-md-3 text-center mb-4">
                    <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                    <h5>Safe & Secure</h5>
                    <p>All vehicles regularly maintained</p>
                </div>
                <div class="col-md-3 text-center mb-4">
                    <i class="bi bi-clock-history display-4 text-primary mb-3"></i>
                    <h5>24/7 Support</h5>
                    <p>Customer support available anytime</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2024 CarRental System. All rights reserved.</p>
            <p class="mt-2">
                <a href="#">Privacy Policy</a> | 
                <a href="#">Terms of Service</a> | 
                <a href="#">Contact Us</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
