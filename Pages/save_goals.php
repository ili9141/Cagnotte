<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in');
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Include database connection file
include('../Backend/db.php');
$db = new Database();
$conn = $db->getConnection();

// Get the submitted form data
$monthlyBudget = $_POST['monthlyBudget'];
$foodLimit = $_POST['foodLimit'];
$transportLimit = $_POST['transportLimit'];
$groceriesLimit = $_POST['groceriesLimit'];
$hobbiesLimit = $_POST['hobbiesLimit'];
$otherLimit = $_POST['otherLimit'];

// Start a transaction to ensure data integrity
try {
    $conn->beginTransaction();

    // Insert into the goals table
    $stmt = $conn->prepare("INSERT INTO goals (user_id, monthly_budget) VALUES (:user_id, :monthly_budget)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':monthly_budget', $monthlyBudget);
    $stmt->execute();

    // Get the ID of the inserted goal record
    $goal_id = $conn->lastInsertId();

    // Insert into the category_goals table for each category
    $categoryLimits = [
        1 => $foodLimit,
        2 => $transportLimit,
        3 => $groceriesLimit,
        4 => $hobbiesLimit,
        5 => $otherLimit
    ];

    foreach ($categoryLimits as $category_id => $category_limit) {
        $stmt = $conn->prepare("INSERT INTO category_goals (user_id, category_id, category_limit) VALUES (:user_id, :category_id, :category_limit)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':category_limit', $category_limit);
        $stmt->execute();
    }

    // Commit the transaction
    $conn->commit();

    // Redirect the user back to the tracker page
    header('Location: tracker.php');
    exit();
} catch (Exception $e) {
    // If there is an error, rollback the transaction
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
