<?php
include '../db/db.php'; // Include the database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Get the current date and time
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');

    // Validate if booking date is in the past
    if ($booking_date < $current_date) {
        $error = "You cannot book a room in the past!";
    } elseif ($booking_date == $current_date && $start_time <= $current_time) {
        // If booking date is today, ensure the start time is in the future
        $error = "Start time must be in the future!";
    } elseif ($end_time <= $start_time) {
        // Ensure end time is after start time
        $error = "End time must be after the start time!";
    } else {
        // Proceed with booking if validation passes
        $query = "INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisss", $_SESSION['user_id'], $room_id, $booking_date, $start_time, $end_time);

        if ($stmt->execute()) {
            // Update room status to unavailable
            $query_update = "UPDATE rooms SET status = 'unavailable' WHERE id = ?";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bind_param("i", $room_id);
            $stmt_update->execute();

            header("Location: confirm_booking.php?id=" . $stmt->insert_id);
            exit();
        } else {
            $error = "Error during booking: " . $stmt->error;
        }
    }
}

$room_id = $_GET['id'];
$query = "SELECT * FROM rooms WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room | EasyBook</title>
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
        .form-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .form-card h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-radius: 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-top: 15px;
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

<!-- Booking Form -->
<div class="container">
    <div class="form-card">
        <h2>Book Room <?php echo htmlspecialchars($room['room_number']); ?></h2>
        <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        
        <form method="POST" action="" onsubmit="return validateBooking()">
            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">

            <!-- Date Picker -->
            <div class="form-group">
                <label>Booking Date</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" name="booking_date" class="form-control" id="booking_date" required>
                </div>
            </div>
            
            <!-- Start Time Picker -->
            <div class="form-group">
                <label>Start Time</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    </div>
                    <input type="time" name="start_time" class="form-control" id="start_time" required>
                </div>
            </div>
            
            <!-- End Time Picker -->
            <div class="form-group">
                <label>End Time</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    </div>
                    <input type="time" name="end_time" class="form-control" id="end_time" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Confirm Booking</button>
        </form>
    </div>
</div>

<!-- Script for Date and Time Validation -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Set the minimum booking date to today
        var today = new Date().toISOString().split('T')[0];
        document.getElementById("booking_date").setAttribute("min", today);
    });

    function validateBooking() {
        var bookingDate = document.getElementById("booking_date").value;
        var startTime = document.getElementById("start_time").value;
        var endTime = document.getElementById("end_time").value;
        var currentDate = new Date().toISOString().split('T')[0];
        var currentTime = new Date().toTimeString().split(' ')[0];

        if (bookingDate === currentDate && startTime <= currentTime) {
            alert("Start time must be in the future.");
            return false;
        }

        if (endTime <= startTime) {
            alert("End time must be after the start time.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
