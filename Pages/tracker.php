<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: ../Pages/login.php");
  exit;
}
$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['user_id']; // Ensure user_id is available in the session
// Include necessary files
include('../components/important-header.php');
include('../components/navb.php');
include('../Backend/db.php'); // Include the database connection file

// Get the database connection
$db = new Database();
$conn = $db->getConnection();

// Prepare the query to get the sum of expenses per category
$query_expenses = "
    SELECT categories.name AS category_name, SUM(expenses.amount) AS total_expenses
    FROM expenses
    JOIN categories ON expenses.category_id = categories.id
    WHERE expenses.user_id = :user_id
    GROUP BY categories.name
";
$stmt_expenses = $conn->prepare($query_expenses);
$stmt_expenses->bindParam(':user_id', $user_id);
$stmt_expenses->execute();
$category_expenses = $stmt_expenses->fetchAll(PDO::FETCH_ASSOC);

// Fetch the category goals
$query_goals = "
    SELECT categories.name AS category_name, category_goals.category_limit
    FROM category_goals
    JOIN categories ON category_goals.category_id = categories.id
    WHERE category_goals.user_id = :user_id
";
$stmt_goals = $conn->prepare($query_goals);
$stmt_goals->bindParam(':user_id', $user_id);
$stmt_goals->execute();
$category_goals = $stmt_goals->fetchAll(PDO::FETCH_ASSOC);

// Merge expenses and goals into a single array
$category_data = [];
foreach ($category_expenses as $category) {
  $category_data[$category['category_name']] = [
    'expense' => $category['total_expenses'],
    'goal' => 0, // Default goal value
  ];
}

foreach ($category_goals as $goal) {
  if (isset($category_data[$goal['category_name']])) {
    $category_data[$goal['category_name']]['goal'] = $goal['category_limit'];
  }
}

// Prepare data for JavaScript
$categories = [];
$expenses = [];
$goals = [];
$category_colors = ['#f94144', '#f3722c', '#f9c74f', '#90be6d', '#577590']; // Predefined color list

foreach ($category_data as $category_name => $data) {
  $categories[] = $category_name;
  $expenses[] = $data['expense'];
  $goals[] = $data['goal'];
}

// Convert PHP arrays to JSON for JavaScript use
$categories_json = json_encode($categories);
$expenses_json = json_encode($expenses);
$goals_json = json_encode($goals);
$category_colors_json = json_encode($category_colors);
?>

<div class="mt-5 mb-5"></div>

<div class="container py-5">
  <h1 class="text-center mb-4" style="color: white">Keep Track of Your Spendings</h1>
  <ul class="nav nav-tabs justify-content-center" id="trackerTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="goals-tab" data-bs-toggle="tab" data-bs-target="#goals" type="button" role="tab">Goals</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="graph-tab" data-bs-toggle="tab" data-bs-target="#graph" type="button" role="tab">Graph</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="add-expense-tab" data-bs-toggle="tab" data-bs-target="#add-expense" type="button" role="tab">Add Expense</button>
    </li>
  </ul>

  <div class="tab-content mt-4" id="trackerTabsContent">
    <!-- Goals Tab -->
    <div class="tab-pane fade show active" id="goals" role="tabpanel">
      <h3>Set Your Monthly Budget</h3>
      <form method="POST" action="save_goals.php">
        <div class="mb-3">
          <label for="monthlyBudget" class="form-label">Monthly Budget</label>
          <input type="number" id="monthlyBudget" name="monthlyBudget" class="form-control" placeholder="Enter your monthly budget" required>
        </div>

        <h4>Category Limits</h4>
        <div class="mb-3">
          <label for="foodLimit" class="form-label">Food</label>
          <input type="number" id="foodLimit" name="foodLimit" class="form-control" placeholder="Limit for Food" required>
        </div>
        <div class="mb-3">
          <label for="transportLimit" class="form-label">Transport</label>
          <input type="number" id="transportLimit" name="transportLimit" class="form-control" placeholder="Limit for Transport" required>
        </div>
        <div class="mb-3">
          <label for="groceriesLimit" class="form-label">Groceries</label>
          <input type="number" id="groceriesLimit" name="groceriesLimit" class="form-control" placeholder="Limit for Groceries" required>
        </div>
        <div class="mb-3">
          <label for="hobbiesLimit" class="form-label">Hobbies</label>
          <input type="number" id="hobbiesLimit" name="hobbiesLimit" class="form-control" placeholder="Limit for Hobbies" required>
        </div>
        <div class="mb-3">
          <label for="otherLimit" class="form-label">Other</label>
          <input type="number" id="otherLimit" name="otherLimit" class="form-control" placeholder="Limit for Other" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary custom-save-btn">Save Goals</button>
        </div>
      </form>
    </div>

    <!-- Graph Tab -->
    <div class="tab-pane fade" id="graph" role="tabpanel">
      <h3>Current Month Expenses</h3>
      <canvas id="expensesChart"></canvas>
    </div>

    <!-- Add Expense Tab -->
    <div class="tab-pane fade" id="add-expense" role="tabpanel">
      <div class="expense-form">
        <h4 class="text-center mb-4">Add an Expense</h4>
        <form method="POST" action="../Backend/add_expense.php">
          <!-- Date Field -->
          <div class="mb-3">
            <label for="expenseDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="expenseDate" name="expenseDate" required>
          </div>

          <!-- Category Field -->
          <div class="mb-3">
            <label for="expenseCategory" class="form-label">Category</label>
            <select class="form-select" id="expenseCategory" name="expenseCategory" required>
              <option value="" disabled selected>Select a category</option>
              <option value="1">Food</option>
              <option value="2">Transport</option>
              <option value="3">Groceries</option>
              <option value="4">Hobbies</option>
              <option value="5">Other</option>
            </select>
          </div>

          <!-- Cost Field -->
          <div class="mb-3">
            <label for="expenseCost" class="form-label">Cost</label>
            <input type="number" class="form-control" id="expenseCost" name="expenseCost" placeholder="Enter the amount" required>
          </div>

          <!-- Comment Field -->
          <div class="mb-3">
            <label for="expenseComment" class="form-label">Comment</label>
            <textarea class="form-control" id="expenseComment" name="expenseComment" rows="3" placeholder="Add a comment (optional)"></textarea>
          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn btn-custom">Add Expense</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Retrieve the categories, expenses, and goals data from PHP
  const categories = <?php echo $categories_json; ?>;
  const expenses = <?php echo $expenses_json; ?>;
  const goals = <?php echo $goals_json; ?>; // The goal values
  const categoryColors = <?php echo $category_colors_json; ?>; // Predefined colors

  // Set up the chart
  const ctx = document.getElementById('expensesChart').getContext('2d');
  const expensesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: categories,
      datasets: [{
          label: 'Expenses',
          data: expenses,
          backgroundColor: categoryColors, // Use the dynamic colors for each category
        },
        {
          label: 'Goals',
          data: goals,
          backgroundColor: categoryColors.map(color => color + '80'), // Lighter version of the color for goals
          borderColor: categoryColors.map(color => color + '80'),
          borderWidth: 1,
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: 'Categories',
          },
        },
        y: {
          title: {
            display: true,
            text: 'Amount ($)',
          },
        },
      },
      plugins: {
        legend: {
          position: 'top',
        },
      },
    },
  });
</script>

<?php include('../components/footer.php'); ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap');

  .custom-save-btn {
    background-color: #f3722c;
  }

  .btn-custom {
    background-color: #f9c74f;
    color: white;
  }
</style>