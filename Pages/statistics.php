<?php
require_once '../Backend/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Fetch all users
    $usersQuery = "SELECT id, name FROM users";
    $stmt = $conn->prepare($usersQuery);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch expenses grouped by user
    $expensesQuery = "
        SELECT 
            eh.user_id, 
            SUM(eh.total_expenses) AS total_spent, 
            eh.category_expenses 
        FROM expense_history eh
        GROUP BY eh.user_id";
    $stmt = $conn->prepare($expensesQuery);
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine user and expense data into statistics
    $statistics = [];
    foreach ($users as $user) {
        $userStats = [
            'id' => $user['id'],
            'name' => $user['name'],
            'total_spent' => 0,
            'category_expenses' => []
        ];
        foreach ($expenses as $expense) {
            if ($expense['user_id'] == $user['id']) {
                $userStats['total_spent'] = (float) $expense['total_spent'];
                $userStats['category_expenses'] = json_decode($expense['category_expenses'], true) ?: [];
            }
        }
        $statistics[] = $userStats;
    }
} catch (Exception $e) {
    echo "<p>Error fetching data: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<div class="statistics-container">
    <h2 class="text-center">User Statistics</h2>

    <!-- Choice Between All Users and Single User -->
    <label for="stats-type" class="form-label">Choose Statistics Type:</label>
    <select id="stats-type" class="form-select">
        <option value="all">All Users</option>
        <option value="single">Single User</option>
    </select>

    <!-- User Selection for Single User -->
    <div id="user-selection" class="mt-3" style="display: none;">
        <label for="user-select" class="form-label">Select a User:</label>
        <select id="user-select" class="form-select">
            <option value="">Choose a user</option>
            <?php foreach ($statistics as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Charts -->
    <div id="all-users-statistics" class="mt-4">
        <h5>All Users</h5>
        <canvas id="allUsersChart"></canvas>
    </div>

    <div id="single-user-statistics" class="mt-4" style="display: none;">
        <h5>Single User</h5>
        <canvas id="singleUserChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statistics = <?= json_encode($statistics, JSON_THROW_ON_ERROR) ?>;

    const allUsersChartCtx = document.getElementById('allUsersChart').getContext('2d');
    const singleUserChartCtx = document.getElementById('singleUserChart').getContext('2d');

    const statsType = document.getElementById('stats-type');
    const userSelect = document.getElementById('user-select');
    const userSelectionDiv = document.getElementById('user-selection');
    const allUsersStatsDiv = document.getElementById('all-users-statistics');
    const singleUserStatsDiv = document.getElementById('single-user-statistics');

    // Generate All Users Chart
    function generateAllUsersChart() {
        const allUsersLabels = statistics.map(user => user.name);
        const allUsersData = statistics.map(user => user.total_spent);

        new Chart(allUsersChartCtx, {
            type: 'bar',
            data: {
                labels: allUsersLabels,
                datasets: [{
                    label: 'Total Spending by User ($)',
                    data: allUsersData,
                    backgroundColor: [
                        '#4CAF50', '#FFC107', '#2196F3', '#FF5722', '#36A2EB', '#FF6384', '#8BC34A', '#9C27B0'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    title: { display: true, text: 'Total Spending by User' },
                },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Amount ($)' } },
                },
            },
        });
    }

    // Generate Single User Chart
    function generateSingleUserChart(userId) {
        const user = statistics.find(user => user.id == userId);
        if (!user || Object.keys(user.category_expenses).length === 0) {
            alert('No data available for the selected user.');
            return;
        }

        const labels = Object.keys(user.category_expenses);
        const data = Object.values(user.category_expenses);

        new Chart(singleUserChartCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: `Spending Breakdown for ${user.name}`,
                    data: data,
                    backgroundColor: [
                        '#F94144', '#F3722C', '#F8961E', '#F9C74F', '#90BE6D', '#43AA8B', '#577590', '#277DA1'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    title: { display: true, text: `Spending Breakdown for ${user.name}` },
                },
            },
        });
    }

    // Handle statistics type selection
    statsType.addEventListener('change', function () {
        if (this.value === 'all') {
            userSelectionDiv.style.display = 'none';
            singleUserStatsDiv.style.display = 'none';
            allUsersStatsDiv.style.display = 'block';
            generateAllUsersChart();
        } else if (this.value === 'single') {
            userSelectionDiv.style.display = 'block';
            allUsersStatsDiv.style.display = 'none';
            singleUserStatsDiv.style.display = 'none';
        }
    });

    // Handle user selection for single user statistics
    userSelect.addEventListener('change', function () {
        const userId = this.value;
        if (userId) {
            singleUserStatsDiv.style.display = 'block';
            generateSingleUserChart(userId);
        } else {
            singleUserStatsDiv.style.display = 'none';
        }
    });

    // Initialize with "All Users" statistics
    generateAllUsersChart();
</script>
