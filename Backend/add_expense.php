<?php
// Include database connection
require_once 'db.php';

// Start the session to use session variables (e.g., user ID)
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit;
}

// Create an instance of the Database class
$database = new Database();
$conn = $database->getConnection();  // Get the PDO connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
    $expense_date = $_POST['expenseDate'];
    $category_id = $_POST['expenseCategory'];
    $amount = $_POST['expenseCost'];
    $description = $_POST['expenseComment'];

    // Validate inputs
    if (empty($expense_date) || empty($category_id) || empty($amount)) {
        $_SESSION['message'] = "All fields are required.";
    } else {
        // Prepare SQL query to insert the expense
        $query = "INSERT INTO expenses (user_id, amount, description, category_id, expense_date) 
                  VALUES (:user_id, :amount, :description, :category_id, :expense_date)";

        if ($stmt = $conn->prepare($query)) {
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':expense_date', $expense_date, PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = "Expense added successfully!";
            } else {
                $_SESSION['message'] = "Error adding expense. Please try again.";
            }
        } else {
            $_SESSION['message'] = "Failed to prepare the SQL query.";
        }
    }

    // Close the connection
    $conn = null;

    // Redirect back to tracker page to display the message
    header("Location: ../pages/tracker.php");
    exit();
}
?>
