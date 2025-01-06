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

<?php include('../components/important-header.php'); ?>

<?php include('../components/navb.php'); ?>

<!-- Carousel Section -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="background: linear-gradient(135deg, #0C134F, #1D267D);">
    <div class="carousel-inner" style="height: 100vh; border-radius: 20px; overflow: hidden;">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <div class="d-flex align-items-center justify-content-center text-center h-100">
                <div>
                    <h1 class="text-white fw-bold">Welcome to La Cagnotte</h1>
                    <p class="text-white lead">Take control of your finances effortlessly with tools to track, manage, and analyze your spending.</p>
                    <a href="#features" class="btn btn-primary btn-lg">Get Started</a>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center text-center h-100">
                <div>
                    <h1 class="text-white fw-bold">Simplify Your Budget</h1>
                    <p class="text-white lead">Visualize your expenses and achieve your financial goals.</p>
                    <a href="#features" class="btn btn-primary btn-lg">Join Us Now</a>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center text-center h-100">
                <div>
                    <h1 class="text-white fw-bold">Track and Analyze</h1>
                    <p class="text-white lead">Stay on top of your spending with detailed insights.</p>
                    <a href="#features" class="btn btn-primary btn-lg">Explore Features</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Combined Info and Features Section -->
<section id="info-features" class="py-5" style="background: linear-gradient(135deg, #0C134F, #1D267D);">
    <div class="container">
        <!-- Info Section -->
        <h2 class="text-center mb-4 text-white">Why Choose La Cagnotte?</h2>
        <p class="lead text-center text-white">La Cagnotte is your ultimate personal finance app, designed to simplify expense tracking and budget management. Take control of your finances and achieve your goals with our seamless tools and insights.</p>

        <!-- Features Section -->
        <div class="row text-center mt-5">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-4 h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <i class="bi bi-gear fs-1"></i>
                        <h5 class="card-title mt-3">Settings</h5>
                        <p class="card-text">View and update your personal information easily.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-4 h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <i class="bi bi-bar-chart fs-1"></i>
                        <h5 class="card-title mt-3">Analytics</h5>
                        <p class="card-text">Set goals, analyze spending patterns, and add expenses effortlessly.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-4 h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <i class="bi bi-clock-history fs-1"></i>
                        <h5 class="card-title mt-3">History</h5>
                        <p class="card-text">Track your expenses by month and export data to Excel with ease.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Button styling */
    .btn-primary {
        background: linear-gradient(45deg, #5C469C, #D4ADFC);
        border: none;
        color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #D4ADFC, #5C469C);
        transform: scale(1.1);
        box-shadow: 0 5px 20px rgba(212, 173, 252, 0.8);
    }

    /* Card glow effects */
    .card {
        background: linear-gradient(145deg, #1D267D, #5C469C);
        color: #fff;
        border-radius: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3), 0 0 15px rgba(212, 173, 252, 0.8);
    }

    /* Icons glow effect */
    i {
        color: #D4ADFC;
        transition: transform 0.3s, text-shadow 0.3s;
    }

    i:hover {
        transform: scale(1.2);
        text-shadow: 0 0 10px rgba(212, 173, 252, 0.8);
    }

    /* Section backgrounds */
    #info-features {
        padding: 60px 20px;
        color: #fff;
    }

    /* Smooth scrolling anchor link */
    html {
        scroll-behavior: smooth;
    }
</style>

<script>
    // Smooth carousel auto-scroll
    const carousel = document.querySelector('#carouselExampleIndicators');
    carousel.addEventListener('slid.bs.carousel', () => {
        setTimeout(() => {
            const nextButton = carousel.querySelector('.carousel-control-next');
            nextButton.click();
        }, 5000); // 5-second delay
    });
</script>

<?php include('../components/footer.php'); ?>
