<?php
include '../db/db.php'; // Database connection
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "UPDATE users SET status = 'approved' WHERE id = $user_id";
    if (mysqli_query($conn, $query)) {
        header('Location: admin_panel.php?status=approved');
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
