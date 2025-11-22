<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: my-bookings.php");
    exit();
}

$bookingId = intval($_GET['id']);
$booking = getBookingById($bookingId);

if (!$booking || ($booking['user_id'] != getCurrentUserId() && !isAdmin())) {
    header("Location: my-bookings.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $bookingId; ?> - Car Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        @media print {
            .no-print { display: none; }
            .invoice { box-shadow: none; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark no-print">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="bi bi-car-front-fill"></i> CarRental</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../cars.php">Browse Cars</a></li>
                    <?php if (!isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="no-print mb-3">
            <a href="<?php echo isAdmin() ? '../admin/view-bookings.php' : 'my-bookings.php'; ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Invoice
            </button>
        </div>
        
        <div class="invoice">
            <div class="invoice-header">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="mb-3"><i class="bi bi-car-front-fill"></i> CarRental</h1>
                        <p class="mb-1"><strong>123 Main Street</strong></p>
                        <p class="mb-1">City, State 12345</p>
                        <p class="mb-1">Phone: (123) 456-7890</p>
                        <p>Email: info@carrental.com</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h2>INVOICE</h2>
                        <p class="mb-1"><strong>Invoice #:</strong> <?php echo str_pad($bookingId, 6, '0', STR_PAD_LEFT); ?></p>
                        <p class="mb-1"><strong>Date:</strong> <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></p>
                        <p><span class="badge status-<?php echo $booking['status']; ?> fs-6">
                            <?php echo ucfirst($booking['status']); ?>
                        </span></p>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Bill To:</h5>
                    <p class="mb-1"><strong><?php echo htmlspecialchars($booking['full_name']); ?></strong></p>
                    <p class="mb-1"><?php echo htmlspecialchars($booking['email']); ?></p>
                    <?php if ($booking['phone']): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($booking['phone']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Rental Period:</h5>
                    <p class="mb-1"><strong>Start Date:</strong> <?php echo date('M d, Y', strtotime($booking['start_date'])); ?></p>
                    <p class="mb-1"><strong>End Date:</strong> <?php echo date('M d, Y', strtotime($booking['end_date'])); ?></p>
                    <p><strong>Duration:</strong> <?php echo $booking['total_days']; ?> day(s)</p>
                </div>
            </div>
            
            <h5>Vehicle Details:</h5>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Registration</th>
                        <th>Rate</th>
                        <th>Days</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($booking['car_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($booking['registration_number']); ?></td>
                        <td>$<?php echo number_format($booking['total_price'] / $booking['total_days'], 2); ?>/day</td>
                        <td><?php echo $booking['total_days']; ?></td>
                        <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <table class="table">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">$<?php echo number_format($booking['total_price'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tax (0%):</strong></td>
                            <td class="text-end">$0.00</td>
                        </tr>
                        <tr class="table-primary">
                            <td><strong>Total Amount:</strong></td>
                            <td class="text-end"><strong>$<?php echo number_format($booking['total_price'], 2); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="mt-5 pt-4 border-top">
                <h5>Terms & Conditions:</h5>
                <ul class="text-muted">
                    <li>Vehicle must be returned with a full tank of fuel</li>
                    <li>Late returns may incur additional charges</li>
                    <li>Driver must have a valid license and be at least 21 years old</li>
                    <li>Insurance coverage is included in the rental price</li>
                    <li>Any damages to the vehicle will be charged to the customer</li>
                </ul>
            </div>
            
            <div class="text-center mt-5 pt-4 border-top">
                <p class="mb-0">Thank you for choosing CarRental!</p>
                <p class="text-muted">For any queries, please contact us at info@carrental.com</p>
            </div>
        </div>
    </div>

    <footer class="text-center no-print">
        <div class="container">
            <p class="mb-0">&copy; 2024 CarRental System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
