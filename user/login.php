<?php
session_start();
include '../db/db.php'; // Database connection

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // Hashing the password with MD5

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Check user status
        if ($user['status'] == 'approved') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header('Location: ../admin/admin_panel.php');
            } else {
                header('Location: home.php');
            }
            exit();
        } else {
            $error = "Your account is not approved yet.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | EasyBook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        .container {
            max-width: 400px; /* Limit container width */
            margin-top: 100px; /* Center vertically */
            background-color: white; /* White background for form */
            padding: 20px;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .logo {
            display: block;
            margin: 0 auto 20px; /* Center logo and add bottom margin */
            width: 100px; /* Adjust logo size */
        }
        .btn-primary {
            background-color: #30BEAA; /* Secondary color */
            border-color: #30BEAA; /* Match border color */
        }
        .btn-primary:hover {
            background-color: #28a99d; /* Darker shade on hover */
            border-color: #28a99d; /* Match border color */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../images/logo.png" alt="Logo" class="logo"> <!-- Adjust the path to your logo -->
        <h2 class="text-center">Login</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>
