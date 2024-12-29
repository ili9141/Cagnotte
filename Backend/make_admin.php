<?php
require_once 'db.php';

// Ensure the user is logged in and has the proper session
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login or error page if not an admin
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Create a database connection
    $db = (new Database())->getConnection();

    // Prepare the update query to change user type to 'admin'
    $query = "UPDATE users SET type = 'admin' WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        // Redirect back to the user management page with success message
        header("Location: admin_dashboard.php?message=User made Admin successfully");
        exit;
    } else {
        // Error message if the update fails
        header("Location: admin_dashboard.php?message=Error updating user to admin");
        exit;
    }
} else {
    // Redirect if no user id is provided
    header("Location: admin_dashboard.php?message=User ID missing");
    exit;
}
