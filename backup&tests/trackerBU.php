<?php include('../components/important-header.php'); ?>
<?php include('../components/navb.php'); ?>

<div class="mt-5 mb-5"></div>

<div class="container py-5">
<h1 class="text-center mb-4" style="color: white">Keep Track of Your Spendings</h1>
  <ul class="nav nav-tabs justify-content-center" id="trackerTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="goals-tab" data-bs-toggle="tab" data-bs-target="#goals" type="button" role="tab">Goals</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="graph-tab" data-bs-toggle="tab" data-bs-target="#graph" type="button" role="tab">Graph</button>
    </li>
  </ul>

  <div class="tab-content mt-4" id="trackerTabsContent">
    <!-- Goals Tab -->
    <div class="tab-pane fade show active" id="goals" role="tabpanel">
      <h3>Set Your Monthly Budget</h3>
      <form>
        <div class="mb-3">
          <label for="monthlyBudget" class="form-label">Monthly Budget</label>
          <input type="number" id="monthlyBudget" class="form-control" placeholder="Enter your monthly budget">
        </div>

        <h4>Category Limits</h4>
        <div class="mb-3">
          <label for="foodLimit" class="form-label">Food</label>
          <input type="number" id="foodLimit" class="form-control" placeholder="Limit for Food">
        </div>
        <div class="mb-3">
          <label for="transportLimit" class="form-label">Transport</label>
          <input type="number" id="transportLimit" class="form-control" placeholder="Limit for Transport">
        </div>
        <div class="mb-3">
          <label for="groceriesLimit" class="form-label">Groceries</label>
          <input type="number" id="groceriesLimit" class="form-control" placeholder="Limit for Groceries">
        </div>
        <div class="mb-3">
          <label for="hobbiesLimit" class="form-label">Hobbies</label>
          <input type="number" id="hobbiesLimit" class="form-control" placeholder="Limit for Hobbies">
        </div>
        <div class="mb-3">
          <label for="otherLimit" class="form-label">Other</label>
          <input type="number" id="otherLimit" class="form-control" placeholder="Limit for Other">
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary custom-save-btn">Save Goals</button>
        </div>
      </form>
    </div>

    <!-- Graph Tab -->
    <div class="tab-pane fade" id="graph" role="tabpanel">
      <h3>Current Month Expenses</h3>
      <canvas id="expensesChart"></canvas>
      <div class="legend">
        <div class="legend-item">
          <div class="legend-color" style="background-color: #f94144;"></div> Food
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #f3722c;"></div> Transport
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #f9c74f;"></div> Groceries
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #90be6d;"></div> Hobbies
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #577590;"></div> Other
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Chart setup
  const ctx = document.getElementById('expensesChart').getContext('2d');
  const expensesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Food', 'Transport', 'Groceries', 'Hobbies', 'Other'],
      datasets: [{
        label: 'Expenses',
        data: [300, 150, 2000, 100, 250], // Example data
        backgroundColor: [
          '#f94144',
          '#f3722c',
          '#f9c74f',
          '#90be6d',
          '#577590'
        ],
      }],
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: 'Categories',
          },
        },
        y: {
          title: {
            display: true,
            text: 'Amount ($)',
          },
        },
      },
    },
  });
</script>

<?php include('../components/footer.php'); ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');

  body {
    font-family: 'Dancing Script', cursive;
  }

  .tab-content {
        background: linear-gradient(to right,  rgb(4, 4, 53), rgb(8, 54, 110));
        border-radius: 15px;
        padding: 20px;
        color: azure;
      }


  .progress-bar {
    border-radius: 10px;
  }

  .legend {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .legend-item {
    display: flex;
    align-items: center;
    margin: 0 10px;
  }

  .legend-color {
    width: 20px;
    height: 20px;
    border-radius: 5px;
    margin-right: 10px;
  }

  canvas {
    max-width: 100%;
    height: auto;
  }

  .nav-tabs .nav-link {
    background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110));
    color: white;
  }

  .nav-tabs .nav-link.active {
    background: linear-gradient(45deg ,#4b007a, #6c04ad,#a82658, #ba4672);
    color: white;
  }
  .custom-save-btn {
    background: linear-gradient(45deg ,#4b007a, #6c04ad,#a82658, #ba4672);
    border-color:linear-gradient(45deg ,#4b007a, #6c04ad,#a82658, #ba4672) ;
    color: white;
  }

  .custom-save-btn:hover {
    background: linear-gradient(to left, rgb(4, 4, 53), rgb(8, 54, 110));
    color: white;
  }
</style>
