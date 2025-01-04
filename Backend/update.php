<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../Pages/login.php");
    exit;
}
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
    $user_type = $_SESSION['user_type'];
} else {
    header("Location: ../Pages/home.php");
    exit;
}

require_once '../Backend/db.php';
require_once '../Backend/User.php';

// Check if user ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../Pages/admin.php?message=missing_id");
    exit;
}

// Database connection and user instance
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Fetch user data
$userData = $user->getUserById($_GET['id']);
if (!$userData) {
    header("Location: ../Pages/admin.php?message=user_not_found");
    exit;
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $user->id = $_POST['user_id'];
        $user->name = trim($_POST['name']);
        $user->email = trim($_POST['email']);
        $user->password = !empty($_POST['password']) ? trim($_POST['password']) : null;

        if ($user->update()) {
            // Redirection avec un message de succès
            header("Location: ../Pages/admin.php?message=User updated successfully!");
            exit;
        } else {
            $error = "Failed to update user.";
        }
    }

    if (isset($_POST['cancel'])) {
        // Redirection sans changement
        header("Location: ../Pages/admin.php?message=Update cancelled.");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update User</title>
</head>

<body>
    <?php include('../components/important-header.php'); ?>
    <?php include('../components/navb.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center">Update User Information</h2>

        <!-- Display error message if any -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Update form -->
        <form method="POST" action="">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($userData['id']) ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($userData['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($userData['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password (Optional)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>

            <div class="text-center">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
