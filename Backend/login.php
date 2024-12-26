<?php
session_start(); // Start the session to store user details after successful login
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $userData = $user->getUserByEmail($email); // Get user by email from the database

    // Debugging: Output user data to see if it's being fetched correctly
    if ($userData) {
        echo '<pre>';
        print_r($userData);  // Print the user data for debugging
        echo '</pre>';
    } else {
        echo 'No user found with this email address.';  // Debug message if no user is found
    }

    // Check if the user exists and if the password is correct
    if ($userData) {
        // Debugging: Check the hashed password stored in the database
        echo 'Hashed password in DB: ' . $userData['password'] . '<br>';

        // Verify the password using password_verify()
        if (password_verify($password, $userData['password'])) {
            // Password is correct, start the session
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['name'];
            $_SESSION['user_type'] = $userData['type'];

            // Redirect to dashboard or home page
            header("Location: ../Pages/home.php");  // Adjust this to your homepage
            exit();
        } else {
            // If the password doesn't match
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../Pages/login.php");  // Go back to the login page
            exit();
        }
    } else {
        // If no user is found
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../Pages/login.php");  // Go back to the login page
        exit();
    }
}
?>
