<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Safely initialize $user_type from the session or set it to null
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;
?>

<div>
  <nav class="navbar navbar-expand-lg navbar-dark gradient-custom">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand" href="../Pages/home.php">
        <img src="../Assets/Images/logonav2.png" alt="logo">
      </a>

      <!-- Navbar Toggler for Mobile -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible wrapper -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left links -->
        <ul class="navbar-nav ms-auto me-4">
          <li class="nav-item">
            <a class="nav-link active" href="../Pages/home.php">
              <i class="fas fa-home"></i> Home
            </a>
          </li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="notificationDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="fas fa-bell"></i>
              <span class="badge bg-danger" id="notificationBadge">0</span> Notifications
            </a>
            <ul class="dropdown-menu dropdown-menu-end" id="notificationMenu" aria-labelledby="notificationDropdown">
  <li class="text-center">
    <span class="dropdown-item-text">Loading notifications...</span>
  </li>
</ul>

          </li>
        </ul>

        <!-- Right links -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="far fa-user"></i> Your Profile
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../Pages/profile.php">Settings</a></li>
              <li><a class="dropdown-item" href="../Pages/tracker.php">Analytics</a></li>
              <li><a class="dropdown-item" href="../Pages/History.php">History</a></li>
              <li><a class="dropdown-item" href="../Backend/logout.php">Log Out</a></li>
            </ul>
          </li>
          <?php if ($user_type === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="../Pages/admin.php">
                <i class="fas fa-cogs"></i> Admin Dashboard
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
 document.addEventListener("DOMContentLoaded", function () {
    const notificationBadge = document.getElementById("notificationBadge");
    const notificationMenu = document.getElementById("notificationMenu");
    // Fetch notifications from the server
    function fetchNotifications() {
        fetch("../Backend/get_notifications.php")
            .then((response) => response.text())
            .then((data) => {
                // Separate notifications and badge count using <!--END-->
                const parts = data.split('<!--END-->');
                const notificationsHtml = parts[0];
                const notificationCount = parts[1];
                // Update badge count
                notificationBadge.textContent = notificationCount;
                // Update notification dropdown menu
                notificationMenu.innerHTML = notificationsHtml;
            })
            .catch((error) => console.error("Fetch error:", error));
    }
    // Fetch notifications on page load
    fetchNotifications();
});
</script>