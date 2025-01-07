<?php
class Expense
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function addExpense($userId, $amount, $description, $categoryId, $expenseDate)
    {
        $query = "INSERT INTO expenses (user_id, amount, description, category_id, expense_date) 
                  VALUES (:user_id, :amount, :description, :category_id, :expense_date)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':expense_date', $expenseDate, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getMonthlyData($userId, $month)
    {
        $query = "
            SELECT 
                DATE_FORMAT(expense_date, '%Y-%m') AS month,
                categories.name AS category_name,
                SUM(amount) AS total_expense
            FROM expenses
            JOIN categories ON expenses.category_id = categories.id
            WHERE user_id = :user_id AND DATE_FORMAT(expense_date, '%Y-%m') = :month
            GROUP BY month, category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
