<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="../Assets/Styles/admin.css" rel="stylesheet">
  <title>Admin Panel</title>
  <style>
    .section-content {
      display: none;
    }

    .section-content.active {
      display: block;
    }

  </style>
</head>
<body>
<?php include('../components/important-header.php'); ?>
<?php include('../components/navb.php'); ?>


  <div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-fill my-4">
      <li class="nav-item">
        <a class="nav-link active" id="user-management-tab" onclick="showSection('user-management')">User Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="expense-management-tab" onclick="showSection('expense-management')">Expense Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="statistics-tab" onclick="showSection('statistics')">Statistics</a>
      </li>
    </ul>

    <!-- Sections -->
    <!-- User Management Section -->
    <div id="user-management" class="section-content active">
      <h4>User Management</h4>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Total Expenses</th>
              <th>Joining Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>JohnDoe</td>
              <td>john.doe@example.com</td>
              <td>$500</td>
              <td>2023-01-15</td>
              <td>
                <button class="btn btn-warning btn-sm">Edit</button>
                <button class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>JaneSmith</td>
              <td>jane.smith@example.com</td>
              <td>$750</td>
              <td>2023-02-10</td>
              <td>
                <button class="btn btn-warning btn-sm">Edit</button>
                <button class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Expense Management Section -->
    <div id="expense-management" class="section-content">
      <h4>Expense Management</h4>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Expense ID</th>
              <th>User</th>
              <th>Date</th>
              <th>Category</th>
              <th>Amount</th>
              <th>Comment</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>101</td>
              <td>JohnDoe</td>
              <td>2023-12-01</td>
              <td>Groceries</td>
              <td>$50</td>
              <td>Weekly groceries</td>
              <td>
                <button class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
            <tr>
              <td>102</td>
              <td>JaneSmith</td>
              <td>2023-12-05</td>
              <td>Transport</td>
              <td>$20</td>
              <td>Taxi fare</td>
              <td>
                <button class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Statistics Section -->
    <div id="statistics" class="section-content">
      <h4>Statistics</h4>
      <div class="text-center">
        <p>Total Users: 2</p>
        <p>Total Monthly Spending: $1250</p>
        <canvas id="categoryChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>

  <!-- Footer Section -->
  <?php include('../components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function showSection(sectionId) {
      const sections = document.querySelectorAll('.section-content');
      const tabs = document.querySelectorAll('.nav-link');

      sections.forEach(section => section.classList.remove('active'));
      tabs.forEach(tab => tab.classList.remove('active'));

      document.getElementById(sectionId).classList.add('active');
      document.getElementById(`${sectionId}-tab`).classList.add('active');
    }

    // Example graph for category-wise spending
    const ctx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Groceries', 'Transport', 'Rent', 'Entertainment'],
        datasets: [{
          label: 'Spending by Category',
          data: [200, 150, 600, 100],
          backgroundColor: ['#4b007a', '#6c04ad', '#a82658', '#ba4672'],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true }
        }
      }
    });
  </script>
</body>
</html>
