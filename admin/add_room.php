<?php
include '../db/db.php'; // Database connection
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    $target_dir = "../images/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($_FILES["room_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["room_image"]["tmp_name"]);
    if ($check === false) {
        $error_message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["room_image"]["size"] > 5000000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error_message = isset($error_message) ? $error_message : "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload the file
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], $target_file)) {
            $room_image = mysqli_real_escape_string($conn, $target_file);
            $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
            $capacity = intval($_POST['capacity']);
            $floor_number = intval($_POST['floor_number']);
            $status = 'available'; // Default status when adding a new room

            // Check if features are set
            $has_smartboard = isset($_POST['has_smartboard']) ? 1 : 0; // Convert to 1 or 0
            $has_projector = isset($_POST['has_projector']) ? 1 : 0; // Convert to 1 or 0
            $has_ac = isset($_POST['has_ac']) ? 1 : 0; // Convert to 1 or 0

            // Check if room number already exists
            $check_query = "SELECT * FROM rooms WHERE room_number = '$room_number'";
            $result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($result) > 0) {
                $error_message = "Error: Room number '$room_number' already exists.";
            } else {
                // Insert query
                $query = "INSERT INTO rooms (room_image, room_number, capacity, floor_number, status, has_smartboard, has_projector, has_ac) 
                          VALUES ('$room_image', '$room_number', $capacity, $floor_number, '$status', $has_smartboard, $has_projector, $has_ac)";

                if (mysqli_query($conn, $query)) {
                    header('Location: admin_panel.php?status=room_added');
                    exit();
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Room | EasyBook</title>
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

    <div class="container form-container">
        <h3>Add New Room</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="room_image">Room Image</label>
                <input type="file" class="form-control" id="room_image" name="room_image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="room_number">Room Number</label>
                <input type="text" class="form-control" id="room_number" name="room_number" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
            </div>
            <div class="form-group">
                <label for="floor_number">Floor Number</label>
                <input type="number" class="form-control" id="floor_number" name="floor_number" required>
            </div>
            <h5>Room Features</h5>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="has_smartboard" name="has_smartboard">
                <label class="form-check-label" for="has_smartboard">Smartboard</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="has_projector" name="has_projector">
                <label class="form-check-label" for="has_projector">Projector</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="has_ac" name="has_ac">
                <label class="form-check-label" for="has_ac">AC</label>
            </div>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
            <?php } ?>
            <button type="submit" class="btn btn-add-room">Add Room</button>
        </form>
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
