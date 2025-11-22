<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$stats = getDashboardStats();
$recentBookings = array_slice(getAllBookings(), 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Car Rental System</title>
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-cars.php">Manage Cars</a>
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
        <h1 class="mb-4">Dashboard</h1>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card purple">
                    <h3><?php echo $stats['total_cars']; ?></h3>
                    <p><i class="bi bi-car-front"></i> Total Cars</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card green">
                    <h3><?php echo $stats['available_cars']; ?></h3>
                    <p><i class="bi bi-check-circle"></i> Available Cars</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card orange">
                    <h3><?php echo $stats['total_bookings']; ?></h3>
                    <p><i class="bi bi-calendar-check"></i> Total Bookings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card blue">
                    <h3><?php echo $stats['total_users']; ?></h3>
                    <p><i class="bi bi-people"></i> Total Customers</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Bookings -->
            <div class="col-lg-12">
                <div class="dashboard-card">
                    <h4 class="mb-4">
                        <i class="bi bi-calendar-event"></i> Recent Bookings
                    </h4>
                    
                    <?php if (empty($recentBookings)): ?>
                        <div class="alert alert-info">No bookings yet.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Car</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentBookings as $booking): ?>
                                        <tr>
                                            <td>#<?php echo $booking['id']; ?></td>
                                            <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($booking['start_date'])); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($booking['end_date'])); ?></td>
                                            <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                                            <td>
                                                <span class="badge status-<?php echo $booking['status']; ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="view-bookings.php" class="btn btn-primary">View All Bookings</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-plus-circle display-4 text-primary mb-3"></i>
                        <h5>Add New Car</h5>
                        <p class="text-muted">Add a new vehicle to your fleet</p>
                        <a href="add-car.php" class="btn btn-primary">Add Car</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-car-front display-4 text-success mb-3"></i>
                        <h5>Manage Cars</h5>
                        <p class="text-muted">View and edit existing cars</p>
                        <a href="manage-cars.php" class="btn btn-success">Manage Cars</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-people display-4 text-info mb-3"></i>
                        <h5>Manage Users</h5>
                        <p class="text-muted">View and manage customers</p>
                        <a href="manage-users.php" class="btn btn-info">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
