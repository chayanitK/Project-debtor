<?php include('conn.php'); ?>

<style>
  .navbar {
    background-color: #6610f2;
  }

  .dropdown-submenu {
    position: relative;
  }

  .dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
  }
</style>

<header class="site-navbar site-navbar-target" role="banner">
  <div class="container">
    <div class="row align-items-center position-relative">
      <nav class="navbar fixed-top navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand" href="?p=home">
            <font color="#FFFFFF">DEBTOR</font>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="?p=home">
                  <font color="#FFFFFF"><i class="fa fa-home" aria-hidden="true"></i></font><span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <font color="#FFFFFF">Management</font>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="?p=GatheringPoint">จุดรวมงาน</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="?p=history">
                  <font color="#FFFFFF">ประวัติการติดตาม</font>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </div>
</header>

<script>
  $(document).ready(function() {
    $('.dropdown-submenu a.test').on('click', function(e) {
      $(this).next('ul').toggle();
      e.stopPropagation();
      e.preventDefault();
    });
  });
</script>