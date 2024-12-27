<?php include('../components/important-header.php'); ?>


<?php include('../components/navb.php'); ?>

<div class="container mt-5">
  <h2 class="text-center">Welcome To The Admin Dashboard</h2>

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
  
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead style="background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672); color: white; font-size: 1rem; text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Total Expenses</th>
            <th>Joining Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody style="background-color: #ffffff; font-size: 0.95rem;">
          <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.1);">
            <td>1</td>
            <td>JohnDoe</td>
            <td>john.doe@example.com</td>
            <td>$500</td>
            <td>2023-01-15</td>
            <td>
              <button class="btn btn-sm" style="background-color: #6c04ad; color: white; border-radius: 3px;">Edit</button>
              <button class="btn btn-sm btn-danger" style="border-radius: 3px;">Delete</button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.1);">
            <td>2</td>
            <td>JaneSmith</td>
            <td>jane.smith@example.com</td>
            <td>$750</td>
            <td>2023-02-10</td>
            <td>
              <button class="btn btn-sm" style="background-color: #6c04ad; color: white; border-radius: 3px;">Edit</button>
              <button class="btn btn-sm btn-danger" style="border-radius: 3px;">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Expense Management Section -->
  <div id="expense-management" class="section-content">

    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead style="background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672); color: white; font-size: 1rem; text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);">
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
        <tbody style="background-color: #ffffff; font-size: 0.95rem;">
          <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.1);">
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
          <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.1);">
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
  <div id="statistics" class="section-content" style="background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110)); border-radius: 20px">
   
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
  const ctxAdmin = document.getElementById('categoryChart').getContext('2d');
  const adminCategoryChart = new Chart(ctxAdmin, {
    type: 'bar',
    data: {
      labels: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other'],
      datasets: [{
        label: 'Expenses',
        data: [300, 150, 2000, 100, 250],
        backgroundColor: ['#f94144', '#f3722c', '#f9c74f', '#90be6d', '#577590']
      }]
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
    }
  });
</script>
</body>
</html>
