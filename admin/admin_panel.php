<?php
include '../db/db.php'; // Database connection
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel | EasyBook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Modern font -->
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: 'Roboto', sans-serif; /* Modern font */
            text-align: center; /* Center body content */
        }
        .navbar {
            background-color: #D9D9D9; /* Gray background */
            color: white;
            padding: 20px; /* Increased padding for bigger nav */
        
        }
        .navbar .navbar-text {
            font-size: 20px; /* Larger font for the college name */
            font-weight: bold;
            margin-left: 30px; /* Spacing for better centering */
            margin-right: 30px; /* Spacing for better centering */
        }
        .table th, .table td {
            vertical-align: middle; /* Center align table content */
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
        <h3>User Management</h3>
        <table class="table table-bordered mx-auto" style="width: 80%;"> <!-- Center table -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'pending') { ?>
                                <a href="approve_user.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Approve</a>
                                <a href="reject_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Reject</a>
                            <?php } else {
                                echo "N/A";
                            } ?>
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
