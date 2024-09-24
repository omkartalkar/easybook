<?php
include '../db/db.php'; // Include the database connection
session_start();

// Cleanup expired bookings
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

$query_cleanup = "
    DELETE FROM bookings 
    WHERE (booking_date < ?) 
    OR (booking_date = ? AND end_time < ?)
";
$stmt_cleanup = $conn->prepare($query_cleanup);
if ($stmt_cleanup) {
    $stmt_cleanup->bind_param("ssi", $current_date, $current_date, $current_time);
    if (!$stmt_cleanup->execute()) {
        error_log("Error executing cleanup query: " . $stmt_cleanup->error);
    }
    $stmt_cleanup->close();
} else {
    error_log("Error preparing cleanup query: " . $conn->error);
}

// Check user session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT b.*, r.room_number FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? AND b.booking_date = CURDATE()";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings | EasyBook</title>
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
        .bottom-nav {
            background-color: #30BEAA; /* Secondary color */
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
            padding: 15px 0;
            display: flex;
            justify-content: space-around;
        }
        .bottom-nav a {
            color: white;
            text-align: center;
            flex: 1;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 80vh;
        }
        .table-container {
            width: 100%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg">
    <a href="javascript:history.back()" class="btn btn-light mr-2">
        <i class="fas fa-arrow-left"></i>
    </a>
    <span class="navbar-text mx-auto">SIES COLLEGE OF ARTS, COMMERCE, AND SCIENCE</span>
    <span class="navbar-text mr-2">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</nav>

<!-- Main Content -->
<div class="container">
    <div class="table-container">
        <h3 class="text-center mb-4">Your Bookings for Today</h3>
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Booking Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($row['start_time']))); ?></td>
                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($row['end_time']))); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info text-center">You have no bookings for today.</div>
        <?php } ?>
    </div>
</div>

<!-- Bottom Navigation -->
<nav class="bottom-nav">
    <a href="home.php" class="d-flex flex-column align-items-center">
        <i class="fas fa-home fa-lg"></i>
        Home
    </a>
    <a href="quick_booking.php" class="d-flex flex-column align-items-center">
        <i class="fas fa-calendar-check fa-lg"></i>
        Quick Booking
    </a>
    <a href="your_bookings.php" class="d-flex flex-column align-items-center">
        <i class="fas fa-book fa-lg"></i>
        Your Bookings
    </a>
</nav>

</body>
</html>
