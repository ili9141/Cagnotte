<?php
// Start the session
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../Pages/login.php");
    exit;
}

$user_type = $_SESSION['user_type'];
// Include the ProfileController
require_once '../Backend/ProfileController.php';

// Instantiate the ProfileController
$profileController = new ProfileController();

// Fetch user profile data
$userProfile = $profileController->viewProfile();

// If there's an error, show an error message
if (isset($userProfile['error'])) {
    echo "<div class='alert alert-danger'>{$userProfile['error']}</div>";
    exit;
}

// Extract user data from the profile
$name = $userProfile['name'];
$email = $userProfile['email'];
$createdAt = date('F j, Y', strtotime($userProfile['created_at'])); // Format the creation date

// Handle form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the updated data
    $updatedName = trim($_POST['name']);
    $updatedEmail = trim($_POST['email']);
    $updatedPassword = trim($_POST['password']);  // Optional password update

    // Perform the update using the ProfileController
    $updateResult = $profileController->updateProfile($updatedName, $updatedEmail, $updatedPassword);

    // If the update is successful, display a success alert
    if ($updateResult) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Profile updated successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Failed to update profile. Please try again.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $deleteResult = $profileController->deleteProfile();

    if (isset($deleteResult['success'])) {
        // Redirect to login page after successful deletion
        header("Location: ../Pages/login.php");
        exit;
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                {$deleteResult['error']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/Styles/styles.css">

</head>

<body>

    <?php include('../components/important-header.php'); ?>
    <?php include('../components/navb.php'); ?>

    <div class="container py-5">
        <!-- Profile Section -->
        <div class="profile-header text-center mb-5">
            <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
            <p class="lead">Here's a summary of your profile information:</p>
        </div>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="profile-card bg-light p-4">
                    <h4>Profile Information</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Joined On:</strong> <?php echo $createdAt; ?></p>

                    <!-- Button to show update form -->
                    <div class="text-center">
                        <span class="show-update-form btn btn-primary">Update Information</span>
                    </div>

                    <!-- Update Profile Form (Initially hidden) -->
                    <div class="update-form mt-4">
                        <h4>Update Your Information</h4>
                        <form method="POST">
                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <!-- Password Field (Optional) -->
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password if you want to change it">
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-custom">Update Information</button>
                            </div>
                        </form>
                    </div>

                    <!-- Temporary message display -->
                    <?php
                    if (isset($_SESSION['update_message'])) {
                        echo "<p class='update-message'>" . $_SESSION['update_message'] . "</p>";
                        unset($_SESSION['update_message']); // Clear the message after displaying
                    }
                    ?>

                    <!-- Delete Account Button -->
                    <div class="text-center mt-4">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                            <button type="submit" name="delete_account" class="btn btn-danger">Delete Account</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript to show the update form when the button is clicked
        document.querySelector('.show-update-form').addEventListener('click', function() {
            document.querySelector('.update-form').style.display = 'block'; // Show the update form
            this.style.display = 'none'; // Hide the "Update Information" button
        });

        // Automatically hide the temporary message after 5 seconds (delay adjusted)
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const message = document.querySelector('.update-message');
                if (message) {
                    message.style.display = 'none';
                }
            }, 5000);
        });
    </script>
</body>

</html>