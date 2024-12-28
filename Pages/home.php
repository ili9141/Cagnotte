<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../Pages/login.php");
    exit;
}
?>
<?php include('../components/important-header.php'); ?>
<?php include('../components/navb.php'); ?>



<div class="mt-5 mb-5"></div>

<!-- Slider Section -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="max-width: 90vw; margin: 0 auto;">
    <div class="carousel-inner" style="height: 100vh;">
        <!-- Slide 1 -->
        <div class="carousel-item active bg-image" style="background-image: url('../Assets/Images/banner1.png'); background-size: cover; background-position: center;">
            <div class="overlay w-100 h-100" style="border-radius: 15px;"></div>
            <div class="content position-relative text-center text-white d-flex flex-column justify-content-center" style="font-family: 'Zilla Slab', cursive;">
                <a href="#features" class="btn btn-primary btn-lg mt-3">Join Us Now</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item bg-image" style="background-image: url('../Assets/Images/banner2.png'); background-size: cover; background-position: center;">
            <div class="overlay w-100 h-100" style="border-radius: 15px;"></div>
            <div class="content position-relative text-center text-white d-flex flex-column justify-content-center" style="font-family: 'Zilla Slab', cursive;">
                <a href="#features" class="btn btn-primary btn-lg mt-3">Join Us Now</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item bg-image" style="background-image: url('../Assets/Images/banner3.png'); background-size: cover; background-position: center;">
            <div class="overlay w-100 h-100" style="border-radius: 15px;"></div>
            <div class="content position-relative text-center text-white d-flex flex-column justify-content-center" style="font-family: 'Zilla Slab', cursive;">
                <a href="#features" class="btn btn-primary btn-lg mt-3">Join Us Now</a>
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

<section id="info" class="container-fluid py-5 mt-5" style="background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110)); color: white; font-family: 'Zilla Slab', cursive; font-weight: bold;">
    <div class="container">
        <p class="lead mb-0">La Cagnotte is your ultimate personal finance app, designed to simplify expense tracking and budget management. Whether you want to monitor your spending, categorize transactions, or set budget limits, La Cagnotte provides a seamless experience. With detailed infographics and insights into your finances, managing your budget has never been easier. Take control of your finances and achieve your financial goals with La Cagnotte!</p>
    </div>
</section>

<section id="cards" class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card" style="background-image: url('../Assets/Images/card1.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div class="card-body text-white d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <h5 class="card-title">Card Title 1</h5>
                    <p class="card-text">Description for card 1</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="background-image: url('../Assets/Images/card2.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div class="card-body text-white d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <h5 class="card-title">Card Title 2</h5>
                    <p class="card-text">Description for card 2</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="background-image: url('../Assets/Images/card3.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div class="card-body text-white d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <h5 class="card-title">Card Title 3</h5>
                    <p class="card-text">Description for card 3</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="background-image: url('../Assets/Images/card4.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div class="card-body text-white d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <h5 class="card-title">Card Title 4</h5>
                    <p class="card-text">Description for card 4</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="background-image: url('../Assets/Images/card5.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div class="card-body text-white d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <h5 class="card-title">Card Title 5</h5>
                    <p class="card-text">Description for card 5</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="background-image: url('../Assets/Images/card6.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div class="card-body text-white d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <h5 class="card-title">Card Title 6</h5>
                    <p class="card-text">Description for card 6</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Smooth scrolling for anchor links
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Carousel interval
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.querySelector('#carouselExampleIndicators');
        $(carousel).carousel({
            interval: 10000
        });
    });
</script>

<?php include('../components/footer.php'); ?>



<style>




.bg-image {
    font-family: Arial, sans-serif;
    position: relative;
}

.btn-primary {
    background: linear-gradient(45deg, #4b007a, #6c04ad, #a82658, #ba4672);
    border-color: #ff69b4;
}

.btn-primary:hover {
    background: linear-gradient(45deg, rgba(255, 105, 180, 0.9), rgba(255, 20, 147, 0.9));
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .bg-image {
        height: 70vh;
    }

    .content h1 {
        font-size: 2.5rem;
    }

    .content p {
        font-size: 1.2rem;
    }
}

.overlay {
   
    border-radius: 15px;
}

.card {
    height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: white;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

.card-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.card-text {
    font-size: 1rem;
    text-align: center;
}
.carousel-inner {
    border-radius: 30px;
}

.bg-image {
    border-radius: 30px;
}
</style>


