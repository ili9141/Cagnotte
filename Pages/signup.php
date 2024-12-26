<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/Styles/styles.css">
    <title>Sign Up</title>
</head>
<body>
    <section class="h-100 gradient-form mt-5">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3" style="background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110)); color: #fff;">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">
                                    <div class="text-center mb-5">
                                        <img src="../Assets/Images/logo.png" style="width: 300px; height: 150px;" alt="logo">
                                        <h4 class="mt-1 mb-4">Create Your Account</h4>
                                    </div>

                                    <?php
                                    // Display validation errors
                                    if (isset($_SESSION['errors'])) {
                                        foreach ($_SESSION['errors'] as $error) {
                                            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
                                        }
                                        unset($_SESSION['errors']);
                                    }

                                    // Display single error message
                                    if (isset($_SESSION['error'])) {
                                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                                        unset($_SESSION['error']);
                                    }

                                    // Get stored form data
                                    $form_data = $_SESSION['form_data'] ?? [];
                                    ?>

                                    <form method="POST" action="../Backend/signup.php">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <div class="form-outline">
                                                    <input type="text" name="first_name" class="form-control" 
                                                           value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>" required>
                                                    <label class="form-label">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <div class="form-outline">
                                                    <input type="text" name="last_name" class="form-control"
                                                           value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>" required>
                                                    <label class="form-label">Last Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input type="email" name="email" class="form-control"
                                                   value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                                            <label class="form-label">Email</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input type="password" name="password" class="form-control" required>
                                            <label class="form-label">Password</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input type="password" name="confirm_password" class="form-control" required>
                                            <label class="form-label">Confirm Password</label>
                                        </div>

                                        <div class="small mb-4 text-white-50">
                                            Password must contain:
                                            <ul>
                                                <li>At least 8 characters</li>
                                                <li>One uppercase letter</li>
                                                <li>One lowercase letter</li>
                                                <li>One number</li>
                                            </ul>
                                        </div>

                                        <div class="text-center pt-1 mb-5 pb-1">
                                            <button class="btn btn-primary btn-block gradient-custom-2 mb-3" 
                                                    style="width: 65%" type="submit">Sign Up</button>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center pb-4">
                                            <p class="mb-0 me-2">Already have an account?</p>
                                            <a href="login.php" class="btn btn-outline-danger">Log in</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                    <h4 class="mb-4">Join Our Community</h4>
                                    <p class="small mb-0">Take control of your finances with ease! Our app helps you track spending, 
                                    analyze habits, and create effective budgets. Whether you're saving for goals or managing daily expenses, 
                                    we provide the tools for smarter financial decisions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>