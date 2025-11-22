<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireLogin();

if (isAdmin()) {
    header("Location: ../admin/dashboard.php");
    exit();
}

$success = '';
$error = '';

if (isset($_GET['cancel'])) {
    $bookingId = intval($_GET['cancel']);
    if (cancelBooking($bookingId, getCurrentUserId())) {
        $success = 'Booking cancelled successfully!';
    } else {
        $error = 'Failed to cancel booking.';
    }
}

$bookings = getUserBookings(getCurrentUserId());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Car Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="bi bi-car-front-fill"></i> CarRental</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cars.php">Browse Cars</a></li>
                    <li class="nav-item"><a class="nav-link active" href="my-bookings.php">My Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-calendar-check"></i> My Bookings</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> You don't have any bookings yet.
                <br><a href="../cars.php" class="alert-link mt-2 d-inline-block">Browse available cars</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($bookings as $booking): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title"><?php echo htmlspecialchars($booking['car_name']); ?></h5>
                                    <span class="badge status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </div>
                                
                                <p class="text-muted mb-2"><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></p>
                                
                                <div class="mb-3">
                                    <i class="bi bi-calendar-event"></i> 
                                    <strong>Start:</strong> <?php echo date('M d, Y', strtotime($booking['start_date'])); ?>
                                    <br>
                                    <i class="bi bi-calendar-event"></i> 
                                    <strong>End:</strong> <?php echo date('M d, Y', strtotime($booking['end_date'])); ?>
                                    <br>
                                    <i class="bi bi-clock"></i> 
                                    <strong>Duration:</strong> <?php echo $booking['total_days']; ?> day(s)
                                </div>
                                
                                <div class="mb-3">
                                    <h4 class="text-primary mb-0">$<?php echo number_format($booking['total_price'], 2); ?></h4>
                                    <small class="text-muted">Total Price</small>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="invoice.php?id=<?php echo $booking['id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-file-text"></i> View Invoice
                                    </a>
                                    
                                    <?php if ($booking['status'] === 'confirmed'): ?>
                                        <a href="?cancel=<?php echo $booking['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Booked on: <?php echo date('M d, Y H:i', strtotime($booking['booking_date'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2024 CarRental System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
