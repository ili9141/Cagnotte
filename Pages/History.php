<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Pages/login.php");
    exit;
}

include('../Backend/db.php');

// Initialize the Database connection
$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("Database connection failed.");
}

// Fetch expense history for the logged-in user
$user_id = $_SESSION['user_id'];

// Fetch expense history
$query = "SELECT * FROM expense_history WHERE user_id = :user_id ORDER BY month DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$expense_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the user's monthly budgets
$query = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, monthly_budget FROM goals WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$monthly_budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map monthly budgets by month for quick access
$monthly_budget_map = [];
foreach ($monthly_budgets as $budget) {
    $monthly_budget_map[$budget['month']] = $budget['monthly_budget'];
}

// Fetch category goals
$query = "
    SELECT categories.name AS category_name, category_goals.category_limit
    FROM category_goals
    JOIN categories ON category_goals.category_id = categories.id
    WHERE category_goals.user_id = :user_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$category_goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map category goals as a key-value array for easy access
$category_goals_map = [];
foreach ($category_goals as $goal) {
    $category_goals_map[$goal['category_name']] = $goal['category_limit'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../components/important-header.php'); ?>
    <?php include('../components/navb.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 800px;
        }

        .transaction-card {
            border-radius: 15px;
            background: #ffffff;
            color: #343a40;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .transaction-header {
            font-size: 1.4rem;
            font-weight: bold;
        }

        .amount {
            font-size: 1.6rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .positive {
            color: #28a745;
        }

        .negative {
            color: #dc3545;
        }

        .no-goal {
            font-size: 1.2rem;
            color: #dc3545;
            font-weight: bold;
        }

        .details-container {
            margin-top: 15px;
        }

        .graph-container {
            height: 300px;
            margin-top: 20px;
        }

        .btn-toggle {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-5">Monthly Expense History</h1>

        <?php if (count($expense_history) > 0): ?>
            <?php foreach ($expense_history as $month_data): ?>
                <?php
                $month = $month_data['month'];
                $total_spent = $month_data['total_expenses'];
                if (isset($monthly_budget_map[$month])) {
                    $monthly_budget = $monthly_budget_map[$month];
                    $difference = $monthly_budget - $total_spent;
                    $difference_class = $difference >= 0 ? 'positive' : 'negative';
                } else {
                    $monthly_budget = null;
                }
                ?>
                <div class="transaction-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="transaction-header"><?php echo htmlspecialchars($month); ?></div>
                            <?php if ($monthly_budget === null): ?>
                                <div class="no-goal">No goal set</div>
                            <?php else: ?>
                                <div class="amount <?php echo $difference_class; ?>">
                                    <?php echo $difference >= 0 ? '+$' . number_format($difference, 2) : '-$' . number_format(abs($difference), 2); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary btn-sm btn-toggle" data-month="<?php echo $month; ?>">Details</button>
                    </div>
                    <div class="details-container" id="details-<?php echo $month; ?>" style="display: none;">
                        <?php
                        $categories = json_decode($month_data['category_expenses'], true);
                        if ($categories):
                            $categories_json = json_encode(array_keys($categories));
                            $expenses_json = json_encode(array_values($categories));
                        ?>
                            <div class="graph-container">
                                <canvas id="graph-<?php echo $month; ?>"></canvas>
                            </div>
                            <ul class="list-group mt-3">
                                <?php foreach ($categories as $category => $amount): ?>
                                    <?php
                                    if (isset($category_goals_map[$category])) {
                                        $goal_difference = $category_goals_map[$category] - $amount;
                                        $goal_class = $goal_difference >= 0 ? 'positive' : 'negative';
                                    } else {
                                        $goal_difference = null;
                                    }
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><?php echo htmlspecialchars($category); ?></span>
                                        <span>
                                            $<?php echo number_format($amount, 2); ?>
                                            <?php if ($goal_difference !== null): ?>
                                                (<span class="<?php echo $goal_class; ?>">
                                                    <?php echo $goal_difference >= 0 ? '+$' . number_format($goal_difference, 2) : '-$' . number_format(abs($goal_difference), 2); ?>
                                                </span>)
                                            <?php else: ?>
                                                <span class="no-goal-message">No goal set</span>
                                            <?php endif; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No data available for this month.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">No expense history found.</p>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function () {
            // Toggle Details
            $('.btn-toggle').on('click', function () {
                const month = $(this).data('month');
                const details = $(`#details-${month}`);
                details.slideToggle();
            });

            // Initialize Charts
            <?php foreach ($expense_history as $month_data): ?>
                <?php
                $month = $month_data['month'];
                $categories = json_decode($month_data['category_expenses'], true);
                if ($categories):
                    $categories_json = json_encode(array_keys($categories));
                    $expenses_json = json_encode(array_values($categories));
                ?>
                new Chart(document.getElementById('graph-<?php echo $month; ?>'), {
                    type: 'doughnut',
                    data: {
                        labels: <?php echo $categories_json; ?>,
                        datasets: [{
                            data: <?php echo $expenses_json; ?>,
                            backgroundColor: [
                                '#f94144', '#f3722c', '#f9c74f', '#90be6d', '#577590',
                                '#43aa8b', '#4d908e', '#f9844a', '#ffafcc', '#7209b7'
                            ],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
                <?php endif; ?>
            <?php endforeach; ?>
        });
    </script>
</body>
<?php include('../components/footer.php'); ?>
</html>
