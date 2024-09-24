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
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Fetch all bookings
$bookings = $conn->query("SELECT b.*, r.room_number, u.name FROM bookings b JOIN rooms r ON b.room_id = r.id JOIN users u ON b.user_id = u.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bookings | EasyBook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
            text-align: center;
        }
        .navbar {
            background-color: #D9D9D9; /* Top navbar color */
            color: white;
            padding: 20px;
        }
        .navbar .navbar-text {
            font-size: 24px;
            font-weight: bold;
        }
        .navbar .btn {
            margin-left: 10px;
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #30BEAA; /* Match secondary color */
            padding: 15px 0; /* Increased padding for better aesthetics */
            display: flex; /* Flexbox for centering */
            justify-content: space-around; /* Equal spacing for the links */
        }
        .bottom-nav a {
            color: white;
            text-align: center;
            width: 20%; /* Adjusted width for better spacing */
        }
        .bottom-nav .nav-link {
            display: inline-block; /* Center links */
            text-decoration: none; /* Remove underline from links */
            padding: 5px; /* Padding around the links */
        }
        .bottom-nav .nav-link:hover {
            color: #fff; /* Change color on hover */
            opacity: 0.8; /* Slightly transparent on hover */
        }
        .welcome-msg {
            font-size: 16px;
            color: black;
        }
        .btn-back {
            background-color: black; /* Back button color */
            color: white;
            border: none; /* No border */
        }
        .form-container {
            margin-top: 50px; /* Space for the top navbar */
            margin-bottom: 60px; /* Space for the bottom navbar */
            max-width: 500px; /* Center the form */
            margin-left: auto;
            margin-right: auto;
        }
        .form-check-inline {
            margin-right: 10px; /* Space between features */
        }
        .btn-add-room {
            background-color: #30BEAA; /* Secondary color */
            color: white;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light">
    <a href="javascript:history.back()" class="btn btn-back"><i class="fas fa-arrow-left"></i></a>
    <span class="navbar-text mx-auto">SIES College of Arts, Science, and Commerce</span>
        <div class="welcome-msg">
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> <a href="../user/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

<div class="container mt-3">
    <h3>Bookings List</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User Name</th>
                <th>Room Number</th>
                <th>Booking Date</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($booking = $bookings->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['id']); ?></td>
                    <td><?php echo htmlspecialchars($booking['name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                    <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="bottom-nav">
        <a href="add_room.php" class="nav-link"><i class="fas fa-plus-circle"></i><br>Add Room</a>
        <a href="rooms.php" class="nav-link"><i class="fas fa-eye"></i><br>View Rooms</a>
        <a href="users.php" class="nav-link"><i class="fas fa-users"></i><br>View Users</a>
        <a href="bookings.php" class="nav-link"><i class="fas fa-calendar-check"></i><br>View Bookings</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
