<?php
session_start();
include '../db/db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all rooms and their current booking status
$query = "
    SELECT r.id, r.room_number, r.status, 
           IFNULL(b.booking_date, 'Available') AS booking_date, 
           IFNULL(b.start_time, '-') AS start_time, 
           IFNULL(b.end_time, '-') AS end_time
    FROM rooms r
    LEFT JOIN bookings b ON r.id = b.room_id 
        AND b.booking_date = CURDATE() 
        AND b.end_time > CURRENT_TIME()
    ORDER BY r.room_number
";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
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
        .occupied {
            color: white;
            background-color: red;
        }
        .available {
            color: white;
            background-color: green;
        }
        .disabled-link {
            pointer-events: none;
            color: grey;
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg">
    <a href="javascript:history.back()" class="btn btn-light mr-2">
        <i class="fas fa-arrow-left"></i>
    </a>
    <span class="navbar-text mx-auto">SIES COLLEGE OF ARTS, COMMERCE, AND SCIENCE</span>
    <span class="navbar-text mr-2">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</nav>

<div class="container mt-5">
    <h2>Quick Booking</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Status</th>
                <th>Booking Date</th>
                <th>Time Period</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td class="<?php echo ($row['status'] == 'unavailable') ? 'occupied' : 'available'; ?>">
                        <?php echo ($row['status'] == 'unavailable') ? 'Occupied' : 'Available'; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_time']) . ' - ' . htmlspecialchars($row['end_time']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'available') { ?>
                            <a href="book_room.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Book Room</a>
                        <?php } else { ?>
                            <span class="btn btn-secondary disabled-link">Occupied</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
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

<?php
// Close the database connection
$conn->close();
?>
