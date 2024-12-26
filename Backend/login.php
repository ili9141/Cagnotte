<?php
session_start(); // Start the session to store user details after successful login
require_once 'db.php';  // Make sure this file contains the correct DB connection
require_once 'User.php'; // Include the User class to use its methods

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data from the POST request
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Initialize the database connection and the User object
    $db = (new Database())->getConnection();
    $user = new User($db);

    // Set email and password properties for the User object
    $user->email = $email;
    $user->password = $password;  // Password entered by the user

    // Attempt to log in the user
    if ($user->login()) {
        // If login is successful, store user details in session variables
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_type'] = $user->type;

        // Redirect to dashboard or home page (adjust as necessary)
        header("Location: ../Pages/home.php");  // Adjust this to your homepage
        exit();
    } else {
        // If login fails, set an error message and redirect back to login page
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../Pages/login.html");  // Go back to the login page
        exit();
    }
}
?>
