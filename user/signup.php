<?php
session_start();
include '../db/db.php'; // Database connection

if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // Hashing the password with MD5
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']); // Getting the phone number input

    // Check if user already exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // Insert new user
        $query = "INSERT INTO users (name, department, email, password, role, phone_number, status) VALUES ('$name', '$department', '$email', '$password', '$role', '$phone_number', 'pending')";
        if (mysqli_query($conn, $query)) {
            $success = "Signup successful! Please wait for admin approval.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Email already exists. Please choose another one.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup | EasyBook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 480px;
            margin-top: 100px;
            margin-bottom: 100px;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }
        .btn-primary {
            background-color: #30BEAA;
            border-color: #30BEAA;
        }
        .btn-primary:hover {
            background-color: #28a99d;
            border-color: #28a99d;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../images/logo.png" alt="Logo" class="logo">
        <h2 class="text-center">Signup</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" class="form-control" id="department" name="department" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+91</span>
                    </div>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="professor">Professor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="signup">Signup</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
