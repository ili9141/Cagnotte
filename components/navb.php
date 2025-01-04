<div>
  <nav class="navbar navbar-expand-lg navbar-dark gradient-custom">
    <div class="container-fluid ms-3">
      <a>
        <img src="../Assets/Images/logonav2.png" style="width: 200px; height: 50px;" alt="logo">
      </a>

      <!-- Collapsible wrapper -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left links -->
        <ul class="navbar-nav ms-auto d-flex flex-row me-5 mt-3 mt-lg-0">
          <li class="nav-item text-center mx-2 mx-lg-1">
            <a class="nav-link active" aria-current="page" href="../Pages/home.php">
              <div>
                <i class="fas fa-home fa-lg mb-1"></i>
              </div>
              Home
            </a>
          </li>

          <!-- Notifications Dropdown -->
          <li class="nav-item dropdown text-center mx-2 mx-lg-1">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="notificationDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <div>
                <i class="fas fa-bell fa-lg mb-1"></i>
                <span class="badge rounded-pill badge-notification bg-danger" id="notificationBadge">0</span>
              </div>
              Notifications
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationMenu">
              <li class="text-center">
                <span class="dropdown-item-text">Loading notifications...</span>
              </li>
            </ul>
          </li>
        </ul>

        <!-- Profile and Admin Links -->
        <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0 me-3">
          <li class="nav-item dropdown text-center mx-2 mx-lg-1">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div>
                <i class="far fa-user fa-lg mb-1"></i>
              </div>
              Your Profile
            </a>
            <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../Pages/profile.php">Settings</a></li>
              <li><a class="dropdown-item" href="../Pages/tracker.php">Analytics</a></li>
              <li><a class="dropdown-item" href="../Backend/logout.php">Log Out</a></li>
            </ul>
          </li>

          <!-- Admin-only navbar item -->
          <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
          <li class="nav-item text-center mx-2 mx-lg-1">
            <a class="nav-link" href="../Pages/admin.php">
              <div>
                <i class="fas fa-cogs fa-lg mb-1"></i>
              </div>
              Admin Dashboard
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</div>

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
