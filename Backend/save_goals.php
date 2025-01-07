<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in');
}

$userId = $_SESSION['user_id']; // Get the logged-in user ID

// Include database connection file
include('db.php');
include('Goals.php');
include('CategoryGoals.php');

$db = new Database();
$conn = $db->getConnection();

$monthlyBudget = $_POST['monthlyBudget'];
$categoryLimits = [
    1 => $_POST['foodLimit'],
    2 => $_POST['transportLimit'],
    3 => $_POST['groceriesLimit'],
    4 => $_POST['hobbiesLimit'],
    5 => $_POST['otherLimit']
];

try {
    // Start a transaction
    $conn->beginTransaction();

    // Create Goal
    $goal = new Goal($conn);
    $goalId = $goal->createGoal($userId, $monthlyBudget);

    // Create Category Goals
    $categoryGoal = new CategoryGoal($conn);
    $categoryGoal->createCategoryGoals($userId, $categoryLimits);

    // Commit the transaction
    $conn->commit();

    // Redirect the user back to the tracker page
    header('Location: ../Pages/tracker.php');
    exit();
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
