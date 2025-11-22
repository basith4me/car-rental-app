<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

// Get car ID
if (!isset($_GET['id'])) {
    header("Location: cars.php");
    exit();
}

$carId = intval($_GET['id']);
$car = getCarById($carId);

if (!$car) {
    header("Location: cars.php");
    exit();
}

// Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_car'])) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    
    if (isAdmin()) {
        $error = 'Admin cannot book cars';
    } else {
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $totalDays = intval($_POST['total_days']);
        $totalPrice = floatval($_POST['total_price']);
        
        $result = createBooking(getCurrentUserId(), $carId, $startDate, $endDate, $totalDays, $totalPrice);
        
        if ($result['success']) {
            header("Location: customer/invoice.php?id=" . $result['booking_id']);
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['car_name']); ?> - Car Rental System</title>
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

    <!-- Car Details -->
    <section class="py-5">
        <div class="container">
            <a href="cars.php" class="btn btn-outline-primary mb-4">
                <i class="bi bi-arrow-left"></i> Back to Cars
            </a>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <!-- Car Image and Info -->
                <div class="col-lg-6 mb-4">
                    <?php if (!empty($car['image'])): ?>
                        <img src="<?php echo htmlspecialchars($car['image']); ?>"
                             alt="<?php echo htmlspecialchars($car['car_name']); ?>"
                             class="car-details-img" style="width: 100%; height: 400px; object-fit: cover; border-radius: 10px;"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'img-placeholder\' style=\'height: 400px; border-radius: 10px;\'><i class=\'bi bi-car-front\'></i></div>';">
                    <?php else: ?>
                        <div class="img-placeholder" style="height: 400px; border-radius: 10px;">
                            <i class="bi bi-car-front"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="car-info-box">
                                <i class="bi bi-speedometer2"></i>
                                <strong>Transmission</strong><br>
                                <?php echo htmlspecialchars($car['transmission']); ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="car-info-box">
                                <i class="bi bi-fuel-pump"></i>
                                <strong>Fuel Type</strong><br>
                                <?php echo htmlspecialchars($car['fuel_type']); ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="car-info-box">
                                <i class="bi bi-people"></i>
                                <strong>Seat Capacity</strong><br>
                                <?php echo htmlspecialchars($car['seat_capacity']); ?> Seats
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="car-info-box">
                                <i class="bi bi-calendar-event"></i>
                                <strong>Year</strong><br>
                                <?php echo htmlspecialchars($car['year']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Form -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h2><?php echo htmlspecialchars($car['car_name']); ?></h2>
                            <h5 class="text-muted mb-3"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                            
                            <div class="mb-3">
                                <span class="badge bg-<?php echo $car['status'] === 'available' ? 'success' : 'warning'; ?> p-2">
                                    <?php echo ucfirst($car['status']); ?>
                                </span>
                                <span class="badge bg-secondary p-2"><?php echo htmlspecialchars($car['color']); ?></span>
                            </div>
                            
                            <h3 class="text-primary mb-4">
                                $<?php echo number_format($car['price_per_day'], 2); ?>
                                <small class="text-muted fs-6">/day</small>
                            </h3>
                            
                            <h5 class="mb-3">Description</h5>
                            <p><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
                            
                            <h5 class="mb-3">Additional Details</h5>
                            <ul>
                                <li><strong>Registration:</strong> <?php echo htmlspecialchars($car['registration_number']); ?></li>
                                <li><strong>Color:</strong> <?php echo htmlspecialchars($car['color']); ?></li>
                                <li><strong>Model Year:</strong> <?php echo htmlspecialchars($car['year']); ?></li>
                            </ul>
                            
                            <?php if ($car['status'] === 'available'): ?>
                                <hr>
                                <h5 class="mb-3">Book This Car</h5>
                                
                                <?php if (!isLoggedIn()): ?>
                                    <div class="alert alert-warning">
                                        Please <a href="login.php" class="alert-link">login</a> to book this car.
                                    </div>
                                <?php elseif (isAdmin()): ?>
                                    <div class="alert alert-info">
                                        Admin users cannot book cars.
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                                        </div>
                                        
                                        <input type="hidden" id="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                                        <input type="hidden" id="total_days" name="total_days" value="1">
                                        <input type="hidden" id="total_price" name="total_price" value="<?php echo $car['price_per_day']; ?>">
                                        
                                        <div id="price_display" class="alert alert-info">
                                            <strong>Booking Summary:</strong><br>
                                            Duration: 1 day(s)<br>
                                            Total Price: $<?php echo number_format($car['price_per_day'], 2); ?>
                                        </div>
                                        
                                        <button type="submit" name="book_car" class="btn btn-success w-100 btn-lg">
                                            <i class="bi bi-calendar-check"></i> Book Now
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-warning mt-4">
                                    <i class="bi bi-exclamation-triangle"></i> This car is currently not available for booking.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
