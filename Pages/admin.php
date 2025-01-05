<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../Pages/login.php");
    exit;
}

if (!isset($_SESSION['user_type'])) {
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

<style>
    .container {
        margin-top: 50px;
        background: linear-gradient(45deg, #1D267D, #5C469C);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    h2 {
        font-size: 2.5rem;
        text-align: center;
        color: #D4ADFC;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        margin-bottom: 30px;
        font-family: 'Advent Pro', sans-serif;
    }

    h4 {
        font-size: 1.8rem;
        color: #D4ADFC;
        margin-bottom: 20px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .nav-pills .nav-link {
        font-size: 1.1rem;
        text-transform: uppercase;
        background: #5C469C;
        color: #fff;
        border-radius: 5px;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .nav-pills .nav-link:hover {
        background: #D4ADFC;
        transform: scale(1.05);
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(45deg, #4b007a, #6c04ad);
        color: #fff;
        font-weight: bold;
    }

    .table {
        width: 100%;
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table thead {
        background: linear-gradient(45deg, #4b007a, #6c04ad);
        text-transform: uppercase;
        color: white;
        font-size: 1rem;
    }

    .table th, .table td {
        padding: 15px;
        text-align: left;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-family: 'Advent Pro', sans-serif;
    }

    .table tbody tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.05);
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.2);
        transition: background 0.3s ease-in-out;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-warning {
        background-color: #D4ADFC;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #5C469C;
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(92, 70, 156, 0.6);
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #b02a37;
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(176, 42, 55, 0.6);
    }

    .btn-success {
        background: linear-gradient(45deg, #4CAF50, #34A853);
        color: #fff;
    }

    .btn-success:hover {
        background: linear-gradient(45deg, #34A853, #4CAF50);
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(52, 168, 83, 0.6);
    }

    .section-content {
        display: none;
        padding: 20px;
        border-radius: 15px;
        background: linear-gradient(45deg, #1D267D, #5C469C);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .section-content.active {
        display: block;
    }

    .alert {
        border-radius: 5px;
        font-size: 1rem;
        font-family: 'Advent Pro', sans-serif;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .alert-info {
        background: linear-gradient(45deg, #4b007a, #6c04ad);
        color: white;
        border: none;
    }
</style>

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

    <!-- User Management Section -->
    <div id="user-management" class="section-content active">
        <h4>User Management</h4>
        <div class="table-responsive">
            <?php
            require_once '../Backend/db.php';

            $database = new Database();
            $conn = $database->getConnection();

            try {
                $query = "SELECT id, name, email, created_at, type FROM users ORDER BY created_at DESC";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<p>Error fetching users: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Joining Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                <td>
                                    <a href="../Backend/update.php?id=<?= $user['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="../Backend/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
                                    <?php if ($user['type'] === 'admin'): ?>
                                        <button class="btn btn-success disabled" disabled>Already Admin</button>
                                    <?php else: ?>
                                        <a href="../Backend/make_admin.php?id=<?= $user['id'] ?>" class="btn btn-success">Make Admin</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expense Management Section -->
    <div id="expense-management" class="section-content">
        <h4>Expense Management</h4>
        <div class="table-responsive">
            <?php
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
                echo "<p>Error fetching expenses: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
            <table class="table table-hover table-striped">
                <thead>
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
    <div id="statistics" class="section-content">
        <?php include('./statistics.php'); ?>
    </div>
</div>

<?php include('../components/footer.php'); ?>

<script>
    // Function to show selected section
    function showSection(sectionId) {
        const sections = document.querySelectorAll(".section-content");
        const tabs = document.querySelectorAll(".nav-link");

        // Hide all sections and remove "active" class from tabs
        sections.forEach((section) => section.classList.remove("active"));
        tabs.forEach((tab) => tab.classList.remove("active"));

        const section = document.getElementById(sectionId);
        const tab = document.getElementById(`${sectionId}-tab`);

        if (section && tab) {
            section.classList.add("active");
            tab.classList.add("active");
        } else {
            console.warn(`Section or tab with ID ${sectionId} not found.`);
        }
    }

    // Set default active section on page load
    document.addEventListener("DOMContentLoaded", () => {
        const defaultSection = document.querySelector(".nav-link.active");
        if (defaultSection) {
            const defaultSectionId = defaultSection.id.replace("-tab", "");
            showSection(defaultSectionId);
        }
    });
</script>
