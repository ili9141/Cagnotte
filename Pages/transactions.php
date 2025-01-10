<?php
// transactions.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../Backend/db.php';

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $expense_id = $_POST['expense_id'] ?? null;

    if ($action === 'update' && $expense_id) {
        $description = $_POST['description'];
        $amount = $_POST['amount'];
        $query = "UPDATE expenses SET description = :description, amount = :amount WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':id', $expense_id);
        $stmt->bindParam(':user_id', $user_id);
        if ($stmt->execute()) {
            $message = "Transaction updated successfully.";
        } else {
            $message = "Failed to update transaction.";
        }
    } elseif ($action === 'delete' && $expense_id) {
        $query = "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $expense_id);
        $stmt->bindParam(':user_id', $user_id);
        if ($stmt->execute()) {
            $message = "Transaction deleted successfully.";
        } else {
            $message = "Failed to delete transaction.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<link rel="icon" type="image/x-icon" href="../Assets/Images/piggy.png">
    <title>User Transactions</title>
    <style>
        .container {
            margin-top: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .btn {
            margin: 5px;
        }
    </style>
    <script>
        function toggleEditForm(expenseId) {
            const form = document.getElementById(`edit-form-${expenseId}`);
            if (form) {
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
</head>
<body>
<?php include('../components/important-header.php'); ?>
<?php include('../components/navb.php'); ?>

<div class="container">
    <h2 class="text-center">Your Transactions</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"> <?= htmlspecialchars($message) ?> </div>
    <?php endif; ?>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT e.id AS expense_id, e.expense_date, c.name AS category_name, e.amount, e.description 
                      FROM expenses e
                      JOIN categories c ON e.category_id = c.id
                      WHERE e.user_id = :user_id
                      ORDER BY e.expense_date DESC";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($expenses as $expense): ?>
                <tr>
                    <td><?= htmlspecialchars($expense['expense_id']) ?></td>
                    <td><?= htmlspecialchars($expense['expense_date']) ?></td>
                    <td><?= htmlspecialchars($expense['category_name']) ?></td>
                    <td><?= htmlspecialchars(number_format($expense['amount'], 2)) ?></td>
                    <td><?= htmlspecialchars($expense['description']) ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="toggleEditForm(<?= $expense['expense_id'] ?>)">Edit</button>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="expense_id" value="<?= $expense['expense_id'] ?>">
                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                        </form>
                        <form method="POST" id="edit-form-<?= $expense['expense_id'] ?>" style="display:none; margin-top:10px;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="expense_id" value="<?= $expense['expense_id'] ?>">
                            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($expense['description']) ?>" required>
                            <input type="number" name="amount" class="form-control" value="<?= htmlspecialchars($expense['amount']) ?>" step="0.01" required>
                            <button class="btn btn-success btn-sm" type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../components/footer.php'); ?>
</body>
</html>
