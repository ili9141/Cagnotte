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
          <!-- Add user rows dynamically -->
          <?php 
          $users = [
            ["1", "JohnDoe", "john.doe@example.com", "$500", "2023-01-15"],
            ["2", "JaneSmith", "jane.smith@example.com", "$750", "2023-02-10"],
            ["3", "MikeJohnson", "mike.johnson@example.com", "$620", "2023-03-08"],
            ["4", "SusanLee", "susan.lee@example.com", "$830", "2023-04-12"],
            ["5", "TomBrown", "tom.brown@example.com", "$410", "2023-05-01"],
            ["6", "AnnaWhite", "anna.white@example.com", "$950", "2023-06-21"],
            ["7", "ChrisGreen", "chris.green@example.com", "$720", "2023-07-14"]
          ];
          foreach ($users as $user) {
            echo "<tr>
                    <td>{$user[0]}</td>
                    <td>{$user[1]}</td>
                    <td>{$user[2]}</td>
                    <td>{$user[3]}</td>
                    <td>{$user[4]}</td>
                    <td>
                      <button class='btn btn-warning btn-sm'>Edit</button>
                      <button class='btn btn-danger btn-sm'>Delete</button>
                      <button class='btn btn-primary btn-sm'>Make Admin</button>
                    </td>
                  </tr>";
          }
          ?>
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
          <!-- Add expenses dynamically -->
          <?php 
          $expenses = [
            ["101", "JohnDoe", "2023-12-01", "Groceries", "$50", "Weekly groceries"],
            ["102", "JaneSmith", "2023-12-02", "Shopping", "$120", "Clothing purchase"],
            ["103", "MikeJohnson", "2023-12-03", "Rent", "$500", "Monthly rent"],
            ["104", "SusanLee", "2023-12-04", "Groceries", "$70", "Weekly groceries"],
            ["105", "TomBrown", "2023-12-06", "Utilities", "$90", "Internet bill"],
            ["106", "AnnaWhite", "2023-12-01", "Entertainment", "$150", "Concert tickets"],
            ["107", "ChrisGreen", "2023-12-02", "Transport", "$35", "Taxi fare"]
          ];
          foreach ($expenses as $expense) {
            echo "<tr>
                    <td>{$expense[0]}</td>
                    <td>{$expense[1]}</td>
                    <td>{$expense[2]}</td>
                    <td>{$expense[3]}</td>
                    <td>{$expense[4]}</td>
                    <td>{$expense[5]}</td>
                    <td>
                      <button class='btn btn-danger btn-sm'>Delete</button>
                    </td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Statistics Section -->
  <div id="statistics" class="section-content">
    <h4>Statistics</h4>
    <div class="text-center mb-4">
      <label for="statistics-select">Choose Statistics:</label>
      <select id="statistics-select" class="form-control w-50 mx-auto">
        <option value="all">All Users</option>
        <option value="user">Single User</option>
      </select>
    </div>
    <div id="all-users-statistics" class="statistics-content">
      <h5>All Users Statistics</h5>
      <canvas id="allUsersChart" width="400" height="200"></canvas>
    </div>
    <div id="single-user-statistics" class="statistics-content" style="display: none;">
      <h5>Single User Statistics</h5>
      <div class="form-group">
        <label for="single-user-select">Select User:</label>
        <select id="single-user-select" class="form-control">
          <option value="1">JohnDoe</option>
          <option value="2">JaneSmith</option>
          <option value="3">MikeJohnson</option>
          <option value="4">SusanLee</option>
          <option value="5">TomBrown</option>
          <option value="6">AnnaWhite</option>
          <option value="7">ChrisGreen</option>
        </select>
      </div>
      <canvas id="singleUserChart" width="400" height="200" class="mt-4"></canvas>
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

    // Masquer toutes les sections et retirer la classe "active" de tous les onglets
    sections.forEach(section => section.classList.remove('active'));
    tabs.forEach(tab => tab.classList.remove('active'));

    // Afficher la section sélectionnée et marquer l'onglet correspondant comme actif
    document.getElementById(sectionId).classList.add('active');
    document.getElementById(`${sectionId}-tab`).classList.add('active');
  }

  // Exemple pour initialiser les graphiques
  const ctxAll = document.getElementById('allUsersChart').getContext('2d');
  new Chart(ctxAll, {
    type: 'bar',
    data: {
      labels: ['JohnDoe', 'JaneSmith', 'MikeJohnson', 'SusanLee', 'TomBrown', 'AnnaWhite', 'ChrisGreen'],
      datasets: [{
        label: 'Total Spending by User',
        data: [500, 750, 620, 830, 410, 950, 720],
        backgroundColor: ['#4b007a', '#6c04ad', '#a82658', '#ba4672', '#e97d6a', '#ffcc33', '#36a2eb'],
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true }
      }
    }
  });

  document.getElementById('statistics-select').addEventListener('change', function () {
    const value = this.value;
    if (value === 'all') {
      document.getElementById('all-users-statistics').style.display = 'block';
      document.getElementById('single-user-statistics').style.display = 'none';
    } else {
      document.getElementById('all-users-statistics').style.display = 'none';
      document.getElementById('single-user-statistics').style.display = 'block';
    }
  });

  // Gestion des statistiques pour un utilisateur spécifique
  const userStats = {
    1: { name: 'JohnDoe', data: [50, 100, 150], categories: ['Groceries', 'Rent', 'Transport'] },
    2: { name: 'JaneSmith', data: [200, 300, 250], categories: ['Shopping', 'Entertainment', 'Utilities'] },
    3: { name: 'MikeJohnson', data: [100, 400, 120], categories: ['Groceries', 'Rent', 'Utilities'] },
    4: { name: 'SusanLee', data: [250, 500, 80], categories: ['Shopping', 'Rent', 'Entertainment'] },
    5: { name: 'TomBrown', data: [90, 50, 120], categories: ['Transport', 'Groceries', 'Utilities'] },
    6: { name: 'AnnaWhite', data: [300, 500, 150], categories: ['Entertainment', 'Rent', 'Groceries'] },
    7: { name: 'ChrisGreen', data: [120, 300, 200], categories: ['Transport', 'Groceries', 'Utilities'] }
  };

  document.getElementById('single-user-select').addEventListener('change', function () {
    const userId = this.value;
    const user = userStats[userId];
    const ctx = document.getElementById('singleUserChart').getContext('2d');

    // Supprimer l'ancien graphique
    if (window.singleUserChartInstance) {
      window.singleUserChartInstance.destroy();
    }

    // Créer un nouveau graphique pour l'utilisateur sélectionné
    window.singleUserChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: user.categories,
        datasets: [{
          label: `Spending by ${user.name}`,
          data: user.data,
          backgroundColor: ['#4b007a', '#6c04ad', '#a82658'],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true }
        }
      }
    });
  });
</script>

</body>
</html>
