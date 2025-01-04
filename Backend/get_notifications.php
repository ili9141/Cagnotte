<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit;
}

include('../Backend/db.php');
$user_id = $_SESSION['user_id'];

$db = new Database();
$conn = $db->getConnection();

$notifications = [];

// Get monthly budget
$query_budget = "SELECT monthly_budget FROM goals WHERE user_id = :user_id LIMIT 1";
$stmt_budget = $conn->prepare($query_budget);
$stmt_budget->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_budget->execute();
$monthly_budget = $stmt_budget->fetch(PDO::FETCH_COLUMN);

// Get total expenses
$query_expenses = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = :user_id";
$stmt_expenses = $conn->prepare($query_expenses);
$stmt_expenses->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_expenses->execute();
$total_expenses = $stmt_expenses->fetch(PDO::FETCH_COLUMN) ?? 0;

// Compare total expenses to monthly budget
if ($total_expenses > $monthly_budget) {
    $notifications[] = "Warning: Your total expenses ($total_expenses) have exceeded your monthly budget ($monthly_budget).";
}

// Check category limits
$query_category_limits = "
    SELECT 
        categories.name AS category_name, 
        COALESCE(SUM(expenses.amount), 0) AS total_expenses, 
        COALESCE(category_goals.category_limit, 0) AS category_limit
    FROM categories
    LEFT JOIN expenses ON expenses.category_id = categories.id AND expenses.user_id = :user_id
    LEFT JOIN category_goals ON category_goals.category_id = categories.id AND category_goals.user_id = :user_id
    GROUP BY categories.id, category_goals.category_limit
";
$stmt_limits = $conn->prepare($query_category_limits);
$stmt_limits->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_limits->execute();

while ($row = $stmt_limits->fetch(PDO::FETCH_ASSOC)) {
    // Fallback in case category_limit is missing or null
    $category_limit = $row['category_limit'] ?? 0;
    if ($row['total_expenses'] > $category_limit) {
        $notifications[] = "Warning: You have exceeded your limit for {$row['category_name']} ({$row['total_expenses']} > {$category_limit}).";
    }
}

// Generate HTML for notifications
if (count($notifications) > 0) {
    $html = "";
    foreach ($notifications as $notification) {
        $html .= "<li><span class='dropdown-item-text text-white'>{$notification}</span></li>";
    }
    // Returning the notification list HTML and count
    echo $html . "<!--END-->" . count($notifications);
} else {
    // Returning "No notifications" message and 0 count
    echo "<li class='text-center'><span class='dropdown-item-text text-white'>No new notifications</span></li><!--END-->0";
}
?>
