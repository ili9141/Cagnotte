<?php
require_once 'db.php';

// Ensure the user is logged in and has the proper session
if ($_SESSION['user_type'] == 'user') {
    // Redirect to login or error page if not an admin
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Create a database connection
    $db = (new Database())->getConnection();

    // Prepare the delete query
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        // Redirect back to the user management page after deletion
        header("Location: ../Pages/admin.php?message=User Deleted Successfully");
        exit;
    } else {
        // Error message if deletion fails
        header("Location: ../Pages/admin.php?message=Error deleting user");
        exit;
    }
} else {
    // Redirect if no user id is provided
    header("Location: ../Pages/admin.php?message=User ID missing");
    exit;
}
