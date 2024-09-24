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

// Fetch available rooms
$query_available = "SELECT * FROM rooms WHERE status = 'available'";
$result_available = mysqli_query($conn, $query_available);

// Fetch unavailable rooms
$query_unavailable = "SELECT * FROM rooms WHERE status = 'unavailable'";
$result_unavailable = mysqli_query($conn, $query_unavailable);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | EasyBook</title>
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
        .container{
            padding-bottom: 4em;
        }
        .bottom-nav {
            background-color: #30BEAA; /* Secondary color */
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
            padding: 20px 0; /* Increased height */
            display: flex;
            justify-content: space-around;
        }
        .bottom-nav a {
            color: white;
            text-align: center;
            flex: 1;
        }
        .room-card {
            margin-bottom: 20px;
        }
        .room-features {
            font-size: 14px;
            color: #6c757d;
        }
        .btn-book {
            background-color: #007bff; /* Button color */
            border-radius: 20px; /* Increased border radius */
            color: white;
        }
        .btn-book:hover {
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

<div class="container mt-4">
    <h3>Available Rooms</h3>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result_available)) { ?>
            <div class="col-md-4 room-card">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($row['room_image']); ?>" class="card-img-top" alt="Room image">
                    <div class="card-body">
                        <h5 class="card-title">Room <?php echo htmlspecialchars($row['room_number']); ?></h5>
                        <p class="room-features">Capacity: <?php echo htmlspecialchars($row['capacity']); ?> | Floor: <?php echo htmlspecialchars($row['floor_number']); ?></p>
                        <p class="room-features">Features: AC, Projector, Smartboard</p>
                        <a href="book_room.php?id=<?php echo $row['id']; ?>" class="btn btn-book">Book</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <h3>Unavailable Rooms</h3>
    <div class="row p-4">
        <?php while ($row = mysqli_fetch_assoc($result_unavailable)) { ?>
            <div class="col-md-4 room-card text-white">
                <div class="card bg-secondary text-white">
                    <img src="<?php echo htmlspecialchars($row['room_image']); ?>" class="card-img-top" alt="Room image">
                    <div class="card-body">
                        <h5 class="card-title">Room <?php echo htmlspecialchars($row['room_number']); ?></h5>
                        <p class="room-features text-white">Unavailable</p>
                        <p class="room-features text-white">Features: AC, Projector, Smartboard</p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<nav class="bottom-nav">
    <a href="home.php" class="d-flex flex-column align-items-center">
        <i class="fas fa-home fa-lg"></i>
        Home
    </a>
    <a href="quickbooking.php" class="d-flex flex-column align-items-center">
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
