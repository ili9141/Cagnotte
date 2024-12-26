<div>
<nav class="navbar navbar-expand-lg navbar-dark gradient-custom">
  <div class="container-fluid ms-3">
    <a><img src="../Assets/Images/logonav2.png"
      style="width: 200px; height: 50px;" alt="logo"></a>
   
   

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left links -->
      <ul class="navbar-nav ms-auto d-flex flex-row me-5 mt-3 mt-lg-0">
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link active" aria-current="page" href="#!">
            <div>
              <i class="fas fa-home fa-lg mb-1"></i>
            </div>
            Home
          </a>
        </li>
        <li class="nav-item text-center mx-2 mx-lg-1">
          <a class="nav-link" href="#!">
            <div>
              <i class="fas fa-envelope fa-lg mb-1"></i>
              <span class="badge rounded-pill badge-notification bg-danger">0</span>
            </div>
           Notifications
          </a>
        </li>
        
     
         
      </ul>
      <ul class="navbar-nav ms-auto d-flex flex-row mt-3 mt-lg-0 me-3">
        <li class="nav-item dropdown text-center mx-2 mx-lg-1">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <div>
              <i class="far fa-user fa-lg mb-1 "></i>
           
            </div>
            Your Profile
          </a>
          <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Analytics</a></li>
            <li><a class="dropdown-item" href="#">Log Out</a></li>
            
            
          </ul>
        </li>
        
      </ul>


    </div>
  </div>
</nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Hover dropdown for Profile */
.nav-item.dropdown:hover .dropdown-menu {
  display: block;
  margin-top: 8px; /* Adjust the space between the dropdown and the navbar */
}

/* Ensures the Home and Notifications items are vertically centered */
.navbar-nav .nav-item {
  display: flex;
  align-items: center;
  height: 100%;
}

/* Centers the icons and text inside the navigation items */
.navbar-nav .nav-link {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Adjust navbar and dropdown colors */
.navbar-dark .navbar-nav .nav-link {
  color: #ffffff;
}

.navbar-dark .navbar-nav .nav-link:hover {
  color: #d4adfc;
}

.navbar-nav .nav-item .dropdown-menu {
  background-color: #0C134F !important; /* Matches navbar background color */
}

.dropdown-menu a {
  color: white;
}

.dropdown-menu a:hover {
  background-color: #1D267D; /* Slight hover effect on menu items */
}

/* Adjust position of navbar items (Home, Notifications) */
.navbar-nav.ms-auto {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}

/* Adjust margin for dropdown items and ensure consistent icon positioning */
.navbar-nav .nav-item .nav-link .d-flex {
  align-items: center;
}

.nav-item .nav-link {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

/* Ensure the icons and text (Notifications and Home) are centered properly */
.navbar-nav .nav-item i {
  font-size: 20px;
}

.navbar-nav .nav-item .badge {
  margin-top: 5px; /* Adjust the badge position */
}

/* Additional tweak to adjust vertical alignment for Notifications */
.nav-item:nth-child(2) .nav-link {
  padding-top: 8px; /* Adjusted padding for better alignment */
}

/* Fixes dropdown menu below profile icon */
.nav-item.dropdown .dropdown-menu {
  position: absolute;
  top: 100%; /* Positions it directly below the icon */
  left: -100px;
  right: 0;
  border-radius: 10px;
}

</style>
