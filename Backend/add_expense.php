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
        try {
            // Insert expense into `expenses` table
            $query = "INSERT INTO expenses (user_id, amount, description, category_id, expense_date) 
                      VALUES (:user_id, :amount, :description, :category_id, :expense_date)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':expense_date', $expense_date, PDO::PARAM_STR);
            $stmt->execute();

            // Update expense history logic
            $month = date('Y-m', strtotime($expense_date));

            // Fetch current month's aggregated data
            $query = "
                SELECT 
                    DATE_FORMAT(expense_date, '%Y-%m') AS month,
                    categories.name AS category_name,
                    SUM(amount) AS total_expense
                FROM expenses
                JOIN categories ON expenses.category_id = categories.id
                WHERE user_id = :user_id AND DATE_FORMAT(expense_date, '%Y-%m') = :month
                GROUP BY month, category_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month);
            $stmt->execute();
            $monthly_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Insert or update `expense_history`
            foreach ($monthly_data as $data) {
                $category_name = $data['category_name'];
                $total_expense = $data['total_expense'];

                // Check if entry exists for the month
                $check_query = "SELECT * FROM expense_history WHERE user_id = :user_id AND month = :month";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $check_stmt->bindParam(':month', $month);
                $check_stmt->execute();
                $existing_entry = $check_stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing_entry) {
                    // Update category expenses and recalculate the total expenses
                    $category_expenses = json_decode($existing_entry['category_expenses'], true);
                    $category_expenses[$category_name] = $total_expense;

                    // Recalculate total expenses
                    $new_total_expenses = array_sum($category_expenses);

                    $category_expenses_json = json_encode($category_expenses);

                    $update_query = "UPDATE expense_history 
                                     SET category_expenses = :category_expenses, total_expenses = :total_expenses, updated_at = NOW() 
                                     WHERE id = :id";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bindParam(':category_expenses', $category_expenses_json);
                    $update_stmt->bindParam(':total_expenses', $new_total_expenses);
                    $update_stmt->bindParam(':id', $existing_entry['id'], PDO::PARAM_INT);
                    $update_stmt->execute();
                } else {
                    // Insert new record
                    $category_expenses = [$category_name => $total_expense];
                    $category_expenses_json = json_encode($category_expenses);

                    $insert_query = "INSERT INTO expense_history (user_id, month, total_expenses, category_expenses) 
                                     VALUES (:user_id, :month, :total_expenses, :category_expenses)";
                    $insert_stmt = $conn->prepare($insert_query);
                    $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $insert_stmt->bindParam(':month', $month);
                    $insert_stmt->bindParam(':total_expenses', $total_expense);
                    $insert_stmt->bindParam(':category_expenses', $category_expenses_json);
                    $insert_stmt->execute();
                }
            }

            $_SESSION['message'] = "Expense added and history updated successfully!";
        } catch (Exception $e) {
            $_SESSION['message'] = "Failed to add expense: " . $e->getMessage();
        }
    }

    // Redirect back to tracker page to display the message
    header("Location: ../pages/tracker.php");
    exit();
}
?>
