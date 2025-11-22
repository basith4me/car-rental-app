<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$bookings = getAllBookings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - Admin</title>
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
                    <li class="nav-item"><a class="nav-link" href="manage-cars.php">Manage Cars</a></li>
                    <li class="nav-item"><a class="nav-link active" href="view-bookings.php">View Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage-users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <h1 class="mb-4">All Bookings</h1>
        
        <div class="dashboard-card">
            <div class="mb-3">
                <input type="text" class="form-control" id="searchInput" 
                       placeholder="Search bookings..." 
                       onkeyup="searchTable('searchInput', 'bookingsTable')">
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Car</th>
                            <th>Registration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Booking Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="11" class="text-center">No bookings found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td>#<?php echo $booking['id']; ?></td>
                                    <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['registration_number']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($booking['start_date'])); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($booking['end_date'])); ?></td>
                                    <td><?php echo $booking['total_days']; ?></td>
                                    <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                                    <td><span class="badge status-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($booking['booking_date'])); ?></td>
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
