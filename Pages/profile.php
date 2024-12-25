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

      .expense-form {
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
          border-radius: 15px;
          padding: 20px;
          background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110));
          color: azure;
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
    </style>
  </head>
<body>

<div class="container py-5">
    <!-- Profile Section -->
    <div class="profile-header text-center mb-5">
        <h1>Welcome, [User Name]</h1>
        <p class="lead">Here's a summary of your profile information:</p>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="profile-card bg-light p-4">
                <h4>Profile Information</h4>
                <p><strong>Name:</strong> [User Full Name]</p>
                <p><strong>Email:</strong> [User Email]</p>
                <p><strong>Joined On:</strong> [Date of Account Creation]</p>
            </div>
        </div>
    </div>

    <!-- Add Expense Section -->
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="expense-form">
                <h4 class="text-center mb-4">Add an Expense</h4>
                <form>
                    <!-- Date Field -->
                    <div class="mb-3">
                        <label for="expenseDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="expenseDate" required>
                    </div>

                    <!-- Category Field -->
                    <div class="mb-3">
                        <label for="expenseCategory" class="form-label">Category</label>
                        <select class="form-select" id="expenseCategory" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="food">Food</option>
                            <option value="transport">Transport</option>
                            <option value="groceries">Groceries</option>
                            <option value="hobbies">Hobbies</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Cost Field -->
                    <div class="mb-3">
                        <label for="expenseCost" class="form-label">Cost</label>
                        <input type="number" class="form-control" id="expenseCost" placeholder="Enter the amount" required>
                    </div>

                    <!-- Comment Field -->
                    <div class="mb-3">
                        <label for="expenseComment" class="form-label">Comment</label>
                        <textarea class="form-control" id="expenseComment" rows="3" placeholder="Add a comment (optional)"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom">Add Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
