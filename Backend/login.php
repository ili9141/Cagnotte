<?php
session_start();
require_once 'db.php';
require_once 'User.php';

// Check for remember me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
    $db = (new Database())->getConnection();
    $user = new User($db);
    
    if ($user->loginWithRememberToken($_COOKIE['user_id'], $_COOKIE['remember_token'])) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_type'] = $user->type;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    $db = (new Database())->getConnection();
    $user = new User($db);
    
    $user->email = $email;
    $user->password = $password;

    if ($user->login($remember)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_type'] = $user->type;
        
        header("Location: ../Pages/home.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../Pages/login.php");
        exit();
    }
}
?>