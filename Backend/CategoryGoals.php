<?php
class CategoryGoal
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function createCategoryGoals($userId, $categoryLimits)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO category_goals (user_id, category_id, category_limit) VALUES (:user_id, :category_id, :category_limit)"
        );

        foreach ($categoryLimits as $categoryId => $categoryLimit) {
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':category_limit', $categoryLimit);
            $stmt->execute();
        }
    }
}
