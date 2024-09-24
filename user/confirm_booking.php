<?php
include '../db/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details (email and phone number)
$user_id = $_SESSION['user_id'];
$user_query = "SELECT email, phone_number FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch booking details
$booking_id = $_GET['id'];
$query = "SELECT b.*, r.room_number FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$email = $user['email']; // Get user's email from database
$phone_number = $user['phone_number']; // Get user's phone number from database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation | EasyBook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #30BEAA; /* Secondary color */
            color: white;
        }
        .container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
        .confirmation-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .confirmation-card h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            margin-right: 10px;
            border-radius: 20px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-secondary:hover, .btn-success:hover {
            opacity: 0.8;
        }
        .icon {
            padding-right: 10px;
        }
        .details {
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg">
    <a href="home.php" class="btn btn-light mr-2">
        <i class="fas fa-home"></i>
    </a>
    <span class="navbar-text mx-auto">SIES COLLEGE OF ARTS, COMMERCE, AND SCIENCE</span>
    <span class="navbar-text mr-2">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</nav>

<!-- Booking Confirmation -->
<div class="container">
    <div class="confirmation-card">
        <h2><i class="fas fa-check-circle text-success"></i> Booking Confirmed!</h2>
        <div class="details">
            <p><i class="fas fa-door-closed icon"></i>Room Number: <strong><?php echo htmlspecialchars($booking['room_number']); ?></strong></p>
            <p><i class="fas fa-calendar-alt icon"></i>Booking Date: <strong><?php echo htmlspecialchars($booking['booking_date']); ?></strong></p>
            <p><i class="fas fa-clock icon"></i>Start Time: <strong><?php echo htmlspecialchars($booking['start_time']); ?></strong></p>
            <p><i class="fas fa-clock icon"></i>End Time: <strong><?php echo htmlspecialchars($booking['end_time']); ?></strong></p>
        </div>
        <p class="text-center mt-4">You can share your booking via:</p>
        
        <div class="text-center">
        <a href="mailto:?subject=Booking%20Confirmation&body=Booking%20Details%0ARoom%20Number:%20<?php echo urlencode(htmlspecialchars($booking['room_number'])); ?>%0ABooking%20Date:%20<?php echo urlencode(htmlspecialchars($booking['booking_date'])); ?>%0AStart%20Time:%20<?php echo urlencode(htmlspecialchars($booking['start_time'])); ?>%0AEnd%20Time:%20<?php echo urlencode(htmlspecialchars($booking['end_time'])); ?>" class="btn btn-secondary">
    <i class="fas fa-envelope"></i> Forward via Email
</a>
<a href="https://wa.me/?text=Booking%20Details%0ARoom%20Number:%20<?php echo urlencode(htmlspecialchars($booking['room_number'])); ?>%0ABooking%20Date:%20<?php echo urlencode(htmlspecialchars($booking['booking_date'])); ?>%0AStart%20Time:%20<?php echo urlencode(htmlspecialchars($booking['start_time'])); ?>%0AEnd%20Time:%20<?php echo urlencode(htmlspecialchars($booking['end_time'])); ?>" class="btn btn-success">
    <i class="fab fa-whatsapp"></i> Forward via WhatsApp
</a>


        </div>
    </div>
</div>

</body>
</html>
