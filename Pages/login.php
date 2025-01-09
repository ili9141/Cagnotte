<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
</head>

<body style="background: linear-gradient(45deg,#0C134F, #1D267D , #5C469C,#D4ADFC);">

  <section class="h-100 gradient-form mt-5 ">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-3"
            style="background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110)); color: #fff;">
            <div class="row g-0">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">
                  <div class="text-center">
                    <img src="../Assets/Images/logo.png" style="width: 300px; height: 150px;" alt="logo">
                  </div>

                  <!-- Login Form -->
                  <form method="POST" action="../Backend/login.php">
                    <p class="mt-2">Please login to your account</p>

                    <?php
                    if (isset($_SESSION['error'])) {
                      echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                      unset($_SESSION['error']);
                    }
                    ?>

                    <div class="mb-4">
                      <input type="email" id="form2Example11" name="email" class="form-control"
                        placeholder="Email address" required>
                      <label class="form-label" for="form2Example11">Email</label>
                    </div>

                    <div class="mb-4">
                      <input type="password" id="form2Example22" name="password" class="form-control" required>
                      <label class="form-label" for="form2Example22">Password</label>
                    </div>

                    <div class="mb-4 form-check">
                      <input type="checkbox" class="form-check-input" id="remember" name="remember">
                      <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <div class="text-center pt-1 mb-5 pb-1">
                      <button style="width: 65%" class="btn btn-primary btn-block gradient-custom-2 mb-3"
                        type="submit">Log in</button><br>
                      <a style="color: #fff;" href="forgot-password.php">Forgot password?</a>
                    </div>

                    <div class="d-flex align-items-center justify-content-center pb-4">
                      <p class="mb-0 me-2">Don't have an account?</p>
                      <a href="signup.php" class="btn btn-outline-danger">Create new</a>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4">&nbsp;&nbsp;&nbsp;&nbsp; Spend Smarter, Track Better</h4>
                  <p class="small mb-0">Take control of your finances with ease! Our app is designed to help you track
                    your spendings, analyze your habits, and create a budget that works for you. Whether you're saving
                    for a big goal, trying to cut down on unnecessary expenses, or simply staying on top of your
                    finances, we provide the tools and insights to empower smarter financial decisions.

                    Start your journey to better money management today!</p>
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