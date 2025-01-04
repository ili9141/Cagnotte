<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../Pages/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../components/important-header.php'); ?>
    <?php include('../components/navb.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        .transaction-card {
            border-radius: 10px;
            background: #fff;
            color: black;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .transaction-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }
        .icon-shopping { background-color: #ffd3d3; }
        .icon-subscription { background-color: #e6d9ff; }
        .icon-food { background-color: #ffe6d3; }
        .icon-salary { background-color: #d4f9e3; }
        .icon-transportation { background-color: #d3e9ff; }
        .filter-modal .modal-content {
            border-radius: 15px;
        }
        .filter-modal .modal-body {
            color: black;
        }
        .btn-apply {
            background-color: #6f42c1;
            color: #fff;
        }
        .btn-apply:hover {
            background-color: #59309e;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4">Expense History</h1>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
        </div>
        <div class="mt-3">
            <!-- Today -->
            <h5 class="text-muted">Today</h5>
            <div class="transaction-card d-flex align-items-center">
                <div class="transaction-icon icon-shopping">
                    <i class="bi bi-basket-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Shopping</h6>
                    <small class="text-muted">Buy some grocery - 10:00 AM</small>
                </div>
                <span class="text-danger">- $120</span>
            </div>
            <div class="transaction-card d-flex align-items-center">
                <div class="transaction-icon icon-subscription">
                    <i class="bi bi-card-text"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Subscription</h6>
                    <small class="text-muted">Disney+ Annual Plan - 3:30 PM</small>
                </div>
                <span class="text-danger">- $80</span>
            </div>
            <div class="transaction-card d-flex align-items-center">
                <div class="transaction-icon icon-food">
                    <i class="bi bi-egg-fried"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Food</h6>
                    <small class="text-muted">Buy a ramen - 7:30 PM</small>
                </div>
                <span class="text-danger">- $32</span>
            </div>

            <!-- Yesterday -->
            <h5 class="text-muted mt-4">Yesterday</h5>
            <div class="transaction-card d-flex align-items-center">
                <div class="transaction-icon icon-salary">
                    <i class="bi bi-cash"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Salary</h6>
                    <small class="text-muted">Salary for July - 4:30 PM</small>
                </div>
                <span class="text-success">+ $5000</span>
            </div>
            <div class="transaction-card d-flex align-items-center">
                <div class="transaction-icon icon-transportation">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Transportation</h6>
                    <small class="text-muted">Charging Tesla - 8:30 PM</small>
                </div>
                <span class="text-danger">- $18</span>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content filter-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Filter By</label>
                            <div>
                                <button type="button" class="btn btn-outline-primary">Expense</button>
                                <button type="button" class="btn btn-outline-primary">Transfer</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <div>
                                <button type="button" class="btn btn-outline-secondary">Highest</button>
                                <button type="button" class="btn btn-outline-secondary">Lowest</button>
                                <button type="button" class="btn btn-outline-secondary">Newest</button>
                                <button type="button" class="btn btn-outline-secondary">Oldest</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select">
                                <option selected>Choose...</option>
                                <option value="1">Shopping</option>
                                <option value="2">Food</option>
                                <option value="3">Transportation</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Reset</button>
                    <button type="button" class="btn btn-apply">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include('../components/footer.php'); ?>
</html>
