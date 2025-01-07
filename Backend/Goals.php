<?php
class Goal
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function createGoal($userId, $monthlyBudget)
    {
        $stmt = $this->conn->prepare("INSERT INTO goals (user_id, monthly_budget) VALUES (:user_id, :monthly_budget)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':monthly_budget', $monthlyBudget);
        $stmt->execute();

        return $this->conn->lastInsertId(); // Return the ID of the inserted goal
    }
}
