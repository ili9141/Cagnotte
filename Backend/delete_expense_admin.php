<?php
require_once 'db.php';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $expense_id = $_GET['id'];

    $database = new Database();
    $conn = $database->getConnection();

    try {
        // Prepare and execute the deletion query
        $query = "DELETE FROM expenses WHERE id = :expense_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':expense_id', $expense_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../Pages/admin.php?message=Expense+Deleted");
        } else {
            echo "Failed to delete the expense.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid expense ID.";
}
