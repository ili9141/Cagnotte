
<?php
ob_clean();
// Assuming you have a PDO connection set up as $conn
header('Content-Type: application/json'); // Set content type to JSON
require_once '../Backend/db.php'; // Ensure your DB connection script is included

// Check database connection

try {
    $db = new Database();
    $conn = $db->getConnection();
    // SQL query to fetch user name and total expenses
    $query = "
        SELECT u.name, SUM(e.total_expenses) AS total_expenses
        FROM expense_history e
        INNER JOIN users u ON e.user_id = u.id
        GROUP BY e.user_id
    ";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch the results as an associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If the query was successful and data was found
    if ($result) {
        echo json_encode($result); // Send data as JSON
    } else {
        // If no data found, send an error message as JSON
        echo json_encode(["error" => "No data found"]);
    }
} catch (PDOException $e) {
    // Catch any PDO exceptions (e.g., database connection errors)
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
}
