<?php
session_start();
require_once 'db.php';  // Ensure db.php has the correct database connection code
require_once 'User.php'; // Ensure the User.php has the User class properly defined

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
         header("Location: ../Pages/signup.html");
        exit();
    }

    // Initialize User object
    $db = (new Database())->getConnection();
    $user = new User($db);

    // Check if email already exists
    $existingUser = $user->getUserByEmail($email);
    if ($existingUser) {
        $_SESSION['error'] = "Email is already registered.";
         header("Location: ../Pages/signup.html");
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Set user data
    $user->name = $first_name . ' ' . $last_name;
    $user->email = $email;
    $user->password = $hashedPassword;  // Store hashed password
    $user->type = 'user'; // Default user type

    // Create user
    if ($user->create()) {
        $_SESSION['success'] = "Account created successfully. You can now log in.";
        header("Location: ../Pages/login.html");  // Redirect to login.html after successful registration
    } else {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: ../Pages/signup.html");  // Redirect back to the signup page on error
    }
    exit();
}
?>
