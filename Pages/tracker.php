<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: ../Pages/login.php");
  exit;}
$user_id = $_SESSION['user_id']; // Ensure user_id is available in the session
// Include necessary files
include('../components/important-header.php');
include('../components/navb.php');
include('../Backend/db.php'); // Include the database connection file

// Get the database connection
$db = new Database();
$conn = $db->getConnection();

// Prepare the query to get the sum of expenses per category
$query = "
    SELECT categories.name AS category_name, SUM(expenses.amount) AS total_expenses
    FROM expenses
    JOIN categories ON expenses.category_id = categories.id
    WHERE expenses.user_id = :user_id
    GROUP BY categories.name
";
$stmt = $conn->prepare($query);

// Assuming user_id is stored in the session (adjust as needed)
$user_id = $_SESSION['user_id']; // Replace with the actual user ID

$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Fetch the results
$category_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data for JavaScript (you can pass it directly as JSON)
$categories = [];
$expenses = [];
$colors = ['#f94144', '#f3722c', '#f9c74f', '#90be6d', '#577590']; // Predefined color list
$category_colors = []; // Will store category names with corresponding colors

foreach ($category_expenses as $index => $category) {
  $categories[] = $category['category_name'];
  $expenses[] = $category['total_expenses'];
  // Assign a color for each category, cycling through the predefined colors
  $category_colors[] = [
    'category' => $category['category_name'],
    'color' => $colors[$index % count($colors)], // Cycle through colors if more than 5 categories
  ];
}

// Convert PHP arrays to JSON for JavaScript use
$categories_json = json_encode($categories);
$expenses_json = json_encode($expenses);
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
  // Retrieve the categories, expenses, and category colors data from PHP
  const categories = <?php echo $categories_json; ?>;
  const expenses = <?php echo $expenses_json; ?>;
  const categoryColors = <?php echo $category_colors_json; ?>;

  // Set up the chart
  const ctx = document.getElementById('expensesChart').getContext('2d');
  const expensesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: categories, // Categories from the database
      datasets: [{
        label: 'Expenses',
        data: expenses, // Expenses from the database
        backgroundColor: categoryColors.map(c => c.color), // Use the dynamic colors for each category
      }],
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
          labels: {
            // Dynamically generate the legend labels
            generateLabels: function(chart) {
              return categoryColors.map(c => ({
                text: c.category,
                fillStyle: c.color,
                strokeStyle: c.color,
                lineWidth: 1,
              }));
            },
            font: {
              size: 18, // Set the font size to make the text bigger
              family: 'Arial', // Set the font family (you can choose any font)
              weight: 'bold', // Set the font weight to bold
              color: 'white', // Set the text color to white
            },
            padding: 20, // Increase the padding for better spacing
          },
        },
      },
    },
  });
</script>




<?php include('../components/footer.php'); ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');

  body {
    font-family: 'Dancing Script', cursive;
  }

  .tab-content {
    background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110));
    border-radius: 15px;
    padding: 20px;
    color: azure;
  }

  .progress-bar {
    border-radius: 10px;
  }

  .legend {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .legend-item {
    display: flex;
    align-items: center;
    margin: 0 10px;
  }

  .legend-color {
    width: 20px;
    height: 20px;
    border-radius: 5px;
    margin-right: 10px;
  }

  canvas {
    max-width: 100%;
    height: auto;
  }

  .nav-tabs .nav-link {
    background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110));
    color: white;
  }

  .nav-tabs .nav-link.active {
    background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672);
    color: white;
  }

  .custom-save-btn {
    background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672);
    border-color: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672);
    color: white;
  }

  .custom-save-btn:hover {
    background: linear-gradient(to left, rgb(4, 4, 53), rgb(8, 54, 110));
    color: white;
  }
</style>