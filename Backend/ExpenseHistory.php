<?php
class ExpenseHistory
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function updateHistory($userId, $month, $monthlyData)
    {
        foreach ($monthlyData as $data) {
            $categoryName = $data['category_name'];
            $totalExpense = $data['total_expense'];

            // Check if entry exists for the month
            $checkQuery = "SELECT * FROM expense_history WHERE user_id = :user_id AND month = :month";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(':month', $month);
            $checkStmt->execute();
            $existingEntry = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingEntry) {
                // Update existing entry
                $categoryExpenses = json_decode($existingEntry['category_expenses'], true);
                $categoryExpenses[$categoryName] = $totalExpense;

                $newTotalExpenses = array_sum($categoryExpenses);
                $categoryExpensesJson = json_encode($categoryExpenses);

                $updateQuery = "UPDATE expense_history 
                                SET category_expenses = :category_expenses, total_expenses = :total_expenses, updated_at = NOW() 
                                WHERE id = :id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':category_expenses', $categoryExpensesJson);
                $updateStmt->bindParam(':total_expenses', $newTotalExpenses);
                $updateStmt->bindParam(':id', $existingEntry['id'], PDO::PARAM_INT);
                $updateStmt->execute();
            } else {
                // Insert new entry
                $categoryExpenses = [$categoryName => $totalExpense];
                $categoryExpensesJson = json_encode($categoryExpenses);

                $insertQuery = "INSERT INTO expense_history (user_id, month, total_expenses, category_expenses) 
                                VALUES (:user_id, :month, :total_expenses, :category_expenses)";
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $insertStmt->bindParam(':month', $month);
                $insertStmt->bindParam(':total_expenses', $totalExpense);
                $insertStmt->bindParam(':category_expenses', $categoryExpensesJson);
                $insertStmt->execute();
            }
        }
    }
}
