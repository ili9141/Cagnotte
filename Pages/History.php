<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Pages/login.php");
    exit;
}

include('../Backend/db.php');

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("Database connection failed.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM expense_history WHERE user_id = :user_id ORDER BY month DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$expense_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "
    SELECT DATE_FORMAT(category_goals.created_at, '%Y-%m') AS goal_month, 
           categories.name AS category_name, 
           category_goals.category_limit
    FROM category_goals
    JOIN categories ON category_goals.category_id = categories.id
    WHERE category_goals.user_id = :user_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$category_goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$category_goals_map = [];
foreach ($category_goals as $goal) {
    $goal_month = $goal['goal_month'];
    $category_name = $goal['category_name'];
    $category_goals_map[$goal_month][$category_name] = $goal['category_limit'];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0C134F, #1D267D);
        }

        .container {
            max-width: 900px;
            padding: 20px;
            margin: auto;
        }

        .transaction-card {
            border-radius: 15px;
            background: linear-gradient(145deg, #5C469C, #1D267D);
            color: #f1f1f1;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .transaction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
        }

        .transaction-header {
            font-size: 1.6rem;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
        }

        .btn-toggle {
            font-size: 0.9rem;
            padding: 8px 12px;
            background: linear-gradient(145deg, #D4ADFC, #5C469C);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-toggle:hover {
            background: linear-gradient(145deg, #5C469C, #D4ADFC);
            box-shadow: 0 5px 15px rgba(212, 173, 252, 0.6);
        }

        .details-container {
            margin-top: 15px;
        }

        .graph-container {
            height: 350px;
            margin-top: 0px;
        }


        .list-group-item {
            background: rgba(255, 255, 255, 0.1);
            color: #f1f1f1;
            border: none;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
        }

        .list-group-item:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
        }

        .positive {
            color: #28a745;
            font-weight: bold;
        }

        .negative {
            color: #dc3545;
            font-weight: bold;
        }

        .no-goal-message {
            color: #D4ADFC;
            font-style: italic;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
        }

        canvas {
            background: #1c1c1c;
            border-radius: 15px;
            padding-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Monthly Expense History</h1>

        <?php if (count($expense_history) > 0): ?>
            <?php foreach ($expense_history as $month_data): ?>
                <?php
                $month = $month_data['month'];
                $total_spent = $month_data['total_expenses'];
                ?>
                <div class="transaction-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="transaction-header"><?php echo htmlspecialchars($month); ?></div>
                        </div>
                        <button class="btn btn-toggle" data-month="<?php echo $month; ?>">Details</button>
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
                                    if (isset($category_goals_map[$month][$category])) {
                                        $goal_difference = $category_goals_map[$month][$category] - $amount;
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
                                                    <?php echo $goal_difference >= 0 ? '+$' . number_format($goal_difference, 2) : '-$' . number_format(abs($goal_difference)); ?>
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
            $('.btn-toggle').on('click', function () {
                const month = $(this).data('month');
                const details = $(`#details-${month}`);
                details.slideToggle();
            });

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
                                    '#0C134F', '#1D267D', '#5C469C', '#D4ADFC', '#FF5D73', '#FFC857', '#4CAF50', '#03A9F4', '#9C27B0', '#FF9800'
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