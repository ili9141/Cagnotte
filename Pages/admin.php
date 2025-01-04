<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: ../Pages/login.php");
  exit;
}
if (isset($_SESSION['user_type'])) {
  $user_type = $_SESSION['user_type'];
} else {
  echo "User type is not set.";
  exit;
}

?>

<?php if (isset($_GET['message'])): ?>
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($_GET['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>






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

  <?php require_once '../Backend/fetch_users.php'; ?>
  <!-- User Management Section -->
  <div id="user-management" class="section-content active">
    <h4>User Management</h4>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead style="background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672); color: white;">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Joining Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['id']) ?></td>
              <td><?= htmlspecialchars($user['name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['created_at']) ?></td>
              <td>
                <a href="../Backend/update.php?id=<?= $user['id'] ?>" class="btn btn-warning">Edit</a>




                <a href="../Backend/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
                <a href="../Backend/make_admin.php?id=<?= $user['id'] ?>" class="btn btn-success">Make Admin</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- edit  -->

  <div id="edit-form-container" style="display: none;" class="mt-4">
    <h4>Edit User</h4>
    <form method="POST" action="../Backend/update.php" class="border p-4 rounded">
      <input type="hidden" id="edit-user-id" name="user_id">
      <div class="mb-3">
        <label for="edit-name" class="form-label">Name</label>
        <input type="text" id="edit-name" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="edit-email" class="form-label">Email</label>
        <input type="email" id="edit-email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="edit-password" class="form-label">Password (Leave empty to keep current)</label>
        <input type="password" id="edit-password" name="password" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
    </form>
  </div>



  <?php

  $database = new Database();
  $conn = $database->getConnection();

  try {
    $query = "
        SELECT 
            e.id AS expense_id, 
            u.name AS user_name, 
            e.expense_date, 
            c.name AS category_name, 
            e.amount, 
            e.description 
        FROM expenses e
        JOIN users u ON e.user_id = u.id
        JOIN categories c ON e.category_id = c.id
        ORDER BY e.expense_date DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  ?>

  <?php


  $database = new Database();
  $conn = $database->getConnection();

  try {
    $query = "
        SELECT 
            e.id AS expense_id, 
            u.name AS user_name, 
            e.expense_date, 
            c.name AS category_name, 
            e.amount, 
            e.description 
        FROM expenses e
        JOIN users u ON e.user_id = u.id
        JOIN categories c ON e.category_id = c.id
        ORDER BY e.expense_date DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  ?>

  <!-- Expense Management Section -->
  <div id="expense-management" class="section-content">
    <h4>Expense Management</h4>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead style="background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672); color: white;">
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
          <?php if (!empty($expenses)): ?>
            <?php foreach ($expenses as $expense): ?>
              <tr>
                <td><?= htmlspecialchars($expense['expense_id']) ?></td>
                <td><?= htmlspecialchars($expense['user_name']) ?></td>
                <td><?= htmlspecialchars($expense['expense_date']) ?></td>
                <td><?= htmlspecialchars($expense['category_name']) ?></td>
                <td><?= htmlspecialchars(number_format($expense['amount'], 2)) ?></td>
                <td><?= htmlspecialchars($expense['description']) ?></td>
                <td>
                  <a href="../Backend/delete_expense_admin.php?id=<?= $expense['expense_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No expenses found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>


  <!-- Statistics Section -->
  <div id="statistics" class="section-content gradient-bg">
    <h4>Statistics</h4>
    <div class="text-center mb-4">
      <label for="statistics-select">Choose Statistics:</label>
      <select id="statistics-select" class="form-control w-50 mx-auto">
        <option value="all">All Users</option>
        <option value="user">Single User</option>
      </select>
    </div>

    <!-- All Users Statistics -->
    <div id="all-users-statistics" class="statistics-content">
      <h5>All Users Statistics</h5>
      <canvas id="allUsersChart" width="400" height="200"></canvas>
    </div>

    <!-- Single User Statistics -->
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


  <!-- Footer Section -->
  <?php include('../components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Function to show selected section
    function showSection(sectionId) {
      const sections = document.querySelectorAll('.section-content');
      const tabs = document.querySelectorAll('.nav-link');

      // Hide all sections and remove "active" class from tabs
      sections.forEach(section => section.classList.remove('active'));
      tabs.forEach(tab => tab.classList.remove('active'));

      // Show the selected section and mark the tab as active
      document.getElementById(sectionId).classList.add('active');
      document.getElementById(`${sectionId}-tab`).classList.add('active');
    }

    // Ensure sections work correctly on initial load
    document.addEventListener('DOMContentLoaded', () => {
      const defaultSection = document.querySelector('.nav-link.active');
      if (defaultSection) {
        const defaultSectionId = defaultSection.id.replace('-tab', '');
        showSection(defaultSectionId);
      }
    });

    // All Users Chart
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
          legend: {
            display: true
          }
        }
      }
    });

    // Single User Chart
    const userStats = {
      1: {
        name: 'JohnDoe',
        data: [150, 200, 150, 100, 90],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      },
      2: {
        name: 'JaneSmith',
        data: [200, 100, 150, 70, 230],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      },
      3: {
        name: 'MikeJohnson',
        data: [100, 400, 120, 60, 150],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      },
      4: {
        name: 'SusanLee',
        data: [250, 500, 80, 140, 200],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      },
      5: {
        name: 'TomBrown',
        data: [90, 50, 120, 50, 130],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      },
      6: {
        name: 'AnnaWhite',
        data: [300, 500, 150, 200, 300],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      },
      7: {
        name: 'ChrisGreen',
        data: [120, 300, 200, 90, 180],
        categories: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other']
      }
    };

    document.getElementById('statistics-select').addEventListener('change', function() {
      const value = this.value;
      document.getElementById('all-users-statistics').style.display = value === 'all' ? 'block' : 'none';
      document.getElementById('single-user-statistics').style.display = value === 'user' ? 'block' : 'none';
    });

    document.getElementById('single-user-select').addEventListener('change', function() {
      const userId = this.value;
      const user = userStats[userId];
      const ctx = document.getElementById('singleUserChart').getContext('2d');

      if (window.singleUserChartInstance) {
        window.singleUserChartInstance.destroy();
      }

      window.singleUserChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: user.categories,
          datasets: [{
            label: `Spending by ${user.name}`,
            data: user.data,
            backgroundColor: ['#f94144', '#f3722c', '#f8961e', '#f9c74f', '#90be6d'],
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: true
            }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Categories'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Amount ($)'
              }
            }
          }
        }
      });
    });
  </script>

  <script>
    // Function to fetch users and update the table
    function fetchUsers() {
      fetch('../Backend/fetch_users.php')
        .then(response => response.json())
        .then(data => {
          const userTableBody = document.getElementById('user-table-body');
          userTableBody.innerHTML = ''; // Clear the table before populating new data

          data.forEach(user => {
            // Assuming you have a function to calculate total expenses, you can replace the static value
            const totalExpenses = "$" + Math.floor(Math.random() * 1000); // Placeholder for total expenses

            const row = document.createElement('tr');
            row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.created_at}</td>
                    <td>
                        <button class='btn btn-warning btn-sm'>Edit</button>
                        <button class='btn btn-danger btn-sm'>Delete</button>
                        <button class='btn btn-primary btn-sm'>Make Admin</button>
                    </td>
                `;
            userTableBody.appendChild(row);
          });
        })
        .catch(error => {
          console.error('Error fetching users:', error);
        });
    }

    // Fetch users when the page loads
    document.addEventListener('DOMContentLoaded', fetchUsers);
  </script>

  <script>
    // Afficher le formulaire d'édition avec les données de l'utilisateur
    function showEditForm(user) {
      document.getElementById('edit-user-id').value = user.id;
      document.getElementById('edit-name').value = user.name;
      document.getElementById('edit-email').value = user.email;
      document.getElementById('edit-password').value = ""; // Reset the password field
      document.getElementById('edit-form-container').style.display = 'block';
      window.scrollTo(0, document.getElementById('edit-form-container').offsetTop);
    }

    // Masquer le formulaire d'édition
    function hideEditForm() {
      document.getElementById('edit-form-container').style.display = 'none';
    }
  </script>




  </body>

  </html>