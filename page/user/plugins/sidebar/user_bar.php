<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="pagination.php" class="brand-link">
    <img src="../../dist/img/logo.ico" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">&ensp;WEB &ensp;|&ensp; <?=htmlspecialchars($_SESSION['section']);?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../../dist/img/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="pagination.php" class="d-block"><?=htmlspecialchars($_SESSION['name']);?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/web_template/page/user/pagination.php") {?>
          <a href="pagination.php" class="nav-link active">
          <?php } else {?>
          <a href="pagination.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon far fa-file-alt"></i>
            <p>
              Pagination
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/web_template/page/user/load_more.php") {?>
          <a href="load_more.php" class="nav-link active">
          <?php } else {?>
          <a href="load_more.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon far fa-file-alt"></i>
            <p>
              Load More
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/web_template/page/user/table_switching.php") {?>
          <a href="table_switching.php" class="nav-link active">
          <?php } else {?>
          <a href="table_switching.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon far fa-file-alt"></i>
            <p>
              Table Switching
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/web_template/page/user/ts_lm.php") {?>
          <a href="ts_lm.php" class="nav-link active">
          <?php } else {?>
          <a href="ts_lm.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon far fa-file-alt"></i>
            <p>
              Table Switch + Load More
            </p>
          </a>
        </li> 
        <li class="nav-item">
          <?php if ($_SERVER['REQUEST_URI'] == "/web_template/page/user/keyup_search.php") {?>
          <a href="keyup_search.php" class="nav-link active">
          <?php } else {?>
          <a href="keyup_search.php" class="nav-link">
          <?php } ?>
            <i class="nav-icon far fa-file-alt"></i>
            <p>
              Keyup Search
            </p>
          </a>
        </li> 
        <?php include 'logout.php';?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
