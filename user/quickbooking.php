<?php
include '../db/db.php'; // Include the database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$time_slots = [
    '09:00:00' => '10:00:00',
    '10:00:00' => '11:00:00',
    '11:00:00' => '12:00:00',
    '14:00:00' => '15:00:00',
    '15:00:00' => '16:00:00',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $booking_date = date('Y-m-d'); // Today's date
    $start_time = $_POST['start_time'];
    $end_time = $time_slots[$start_time];

    // Insert booking into the database
    $query = "INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisss", $user_id, $room_id, $booking_date, $start_time, $end_time);

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

// Fetch available rooms
$query_available = "SELECT * FROM rooms WHERE status = 'available'";
$result_available = mysqli_query($conn, $query_available);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Booking | EasyBook</title>
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
            padding: 15px 0; /* Increased height */
            display: flex;
            justify-content: space-around;
        }
        .bottom-nav a {
            color: white;
            text-align: center;
            flex: 1;
        }
        .form-container {
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
        }
        .btn-quick-book {
            background-color: #007bff; /* Primary color */
            border-radius: 20px;
            color: white;
        }
        .btn-quick-book:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <a href="javascript:history.back()" class="btn btn-light mr-2">
        <i class="fas fa-arrow-left"></i>
    </a>
    <span class="navbar-text mx-auto">SIES COLLEGE OF ARTS, COMMERCE, AND SCIENCE</span>
    <span class="navbar-text mr-2">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</nav>

<div class="container form-container">
    <div class="form-card col-md-6">
        <h2 class="text-center mb-4">Quick Booking</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="room_id">Available Rooms</label>
                <select name="room_id" id="room_id" class="form-control" required>
                    <?php while ($row = mysqli_fetch_assoc($result_available)) { ?>
                        <option value="<?php echo $row['id']; ?>">Room <?php echo $row['room_number']; ?> - Capacity: <?php echo $row['capacity']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start_time">Time Slot</label>
                <select name="start_time" id="start_time" class="form-control" required>
                    <?php foreach ($time_slots as $start => $end) { ?>
                        <option value="<?php echo $start; ?>"><?php echo date('h:i A', strtotime($start)); ?> to <?php echo date('h:i A', strtotime($end)); ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-quick-book btn-block">Quick Book</button>
        </form>
    </div>
</div>

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
