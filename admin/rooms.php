<?php
include '../db/db.php'; // Include the database connection
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Handle room deletion
if (isset($_GET['delete'])) {
    $room_id = intval($_GET['delete']); // Get room ID from URL
    $delete_query = "DELETE FROM rooms WHERE id = $room_id";

    if ($conn->query($delete_query) === TRUE) {
        header("Location: rooms.php?status=room_deleted"); // Redirect back to rooms page
        exit();
    } else {
        echo "Error deleting room: " . $conn->error;
    }
}

// Fetch rooms from the database
$rooms = $conn->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rooms | EasyBook</title>
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
    <h3>Rooms List</h3>
    <?php if (isset($_GET['status']) && $_GET['status'] == 'room_deleted') { ?>
        <div class="alert alert-success" role="alert">Room deleted successfully!</div>
    <?php } ?>
    <table class="table">
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($room = $rooms->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($room['capacity']); ?></td>
                    <td><?php echo htmlspecialchars($room['status']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $room['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                    </td>
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
