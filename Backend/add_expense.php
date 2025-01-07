<?php
// Include database connection and required classes
require_once 'db.php';
require_once 'Expense.php';
require_once 'ExpenseHistory.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit;
}

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $expenseDate = $_POST['expenseDate'];
    $categoryId = $_POST['expenseCategory'];
    $amount = $_POST['expenseCost'];
    $description = $_POST['expenseComment'];

    if (empty($expenseDate) || empty($categoryId) || empty($amount)) {
        $_SESSION['message'] = "All fields are required.";
    } else {
        try {
            $expense = new Expense($conn);
            $expenseHistory = new ExpenseHistory($conn);

            // Add the expense
            $expense->addExpense($userId, $amount, $description, $categoryId, $expenseDate);

            // Update the expense history
            $month = date('Y-m', strtotime($expenseDate));
            $monthlyData = $expense->getMonthlyData($userId, $month);
            $expenseHistory->updateHistory($userId, $month, $monthlyData);

            $_SESSION['message'] = "Expense added and history updated successfully!";
        } catch (Exception $e) {
            $_SESSION['message'] = "Failed to add expense: " . $e->getMessage();
        }
    }

    // Redirect back to tracker page
    header("Location: ../pages/tracker.php");
    exit;
}
