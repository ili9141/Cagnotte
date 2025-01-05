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
