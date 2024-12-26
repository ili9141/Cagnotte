<?php
session_start();
require_once 'db.php';
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    
    // Retrieve and sanitize form data
    $first_name = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING));
    $last_name = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate first name
    if (empty($first_name) || strlen($first_name) < 2) {
        $errors[] = "First name must be at least 2 characters long.";
    }
    if (!preg_match("/^[a-zA-Z\s-]*$/", $first_name)) {
        $errors[] = "First name can only contain letters, spaces, and hyphens.";
    }

    // Validate last name
    if (empty($last_name) || strlen($last_name) < 2) {
        $errors[] = "Last name must be at least 2 characters long.";
    }
    if (!preg_match("/^[a-zA-Z\s-]*$/", $last_name)) {
        $errors[] = "Last name can only contain letters, spaces, and hyphens.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one number.";
    }

    // Validate password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If there are any validation errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ];
        header("Location: ../Pages/signup.php");
        exit();
    }

    // Initialize User object
    $db = (new Database())->getConnection();
    $user = new User($db);

    // Check if email already exists
    $existingUser = $user->getUserByEmail($email);
    if ($existingUser) {
        $_SESSION['error'] = "Email is already registered.";
        $_SESSION['form_data'] = [
            'first_name' => $first_name,
            'last_name' => $last_name
        ];
        header("Location: ../Pages/signup.php");
        exit();
    }

    // Set user data
    $user->name = $first_name . ' ' . $last_name;
    $user->email = $email;
    $user->password = $password;  // Will be hashed in create()
    $user->type = 'user';

    // Create user
    try {
        if ($user->create()) {
            // Clear any stored form data
            unset($_SESSION['form_data']);
            
            $_SESSION['success'] = "Account created successfully! Please log in.";
            header("Location: ../Pages/login.php");
        } else {
            throw new Exception("Failed to create account");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred while creating your account. Please try again.";
        $_SESSION['form_data'] = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ];
        // Log the error for administrator review
        error_log("Signup error: " . $e->getMessage());
        header("Location: ../Pages/signup.php");
    }
    exit();
}
?>