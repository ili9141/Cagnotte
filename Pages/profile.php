<?php
// Start the session
session_start();

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
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/Styles/styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@400;600;700&display=swap');

        body {
            font-family: 'Zilla Slab', serif;
        }

        .profile-header {
            background-image: url('../Assets/Images/banner4.png');
            color: white;
            border-radius: 15px;
            padding: 20px;
        }

        .profile-header h1 {
            font-size: 2.5rem;
        }

        .profile-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110));
            color: azure;
        }

        .update-form {
            display: none; /* Hide the update form initially */
        }

        .btn-custom {
            background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672);
            border: none;
            color: white;
        }

        .btn-custom:hover {
            background: linear-gradient(45deg, rgba(255, 105, 180, 0.9), rgba(255, 20, 147, 0.9));
            transform: scale(1.05);
        }

        .show-update-form {
            cursor: pointer;
            text-decoration: underline;
        }

        .update-message {
            margin-top: 20px;
        }
    </style>
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
                        <a href="delete_account.php" class="btn btn-danger">Delete Account</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript to show the update form when the button is clicked
        document.querySelector('.show-update-form').addEventListener('click', function() {
            document.querySelector('.update-form').style.display = 'block';  // Show the update form
            this.style.display = 'none';  // Hide the "Update Information" button
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
