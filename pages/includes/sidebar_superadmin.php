<?php 
    function isActive ($data) {
        $array = explode('/', $_SERVER['REQUEST_URI']);
        $key = array_search("pages", $array);
        $name = $array[$key + 1];
        return $name === $data ? 'active' : '' ;
    }
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars fa-2x"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto ">
        <li class="nav-item d-md-none d-block">
            <a href="../dashboard_superadmin/">
                <img src="../../assets/images/PLogo.png" 
                    alt="Admin Logo" 
                    width="50px"
                    class="img-circle elevation-3">
                <span class="font-weight-light pl-1">Nature Conservation</span>
            </a>
        </li>
        <!-- <li class="nav-item d-md-block d-none">
            <a class="nav-link">เข้าสู่ระบบครั้งล่าสุด: <?php echo $_SESSION['EMP_LOGIN'] ?>  </a>
        </li> -->
    </ul>
</nav>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../assets/images/avatar.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="../employees/" class="d-block"> <?php echo $_SESSION['EMP_NAME']?> </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="../dashboard_superadmin/" class="nav-link <?php echo isActive('dashboard') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>หน้าหลัก</p>
                    </a>
                </li>
                <br>

                <li class="nav-header">ข้อมูลพื้นฐาน</li>
                <li class="nav-item">
                    <a href="../employees/" class="nav-link <?php echo isActive('employees') ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>ข้อมูลพนักงาน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../customer/" class="nav-link <?php echo isActive('customer') ?>">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>ข้อมูลลูกค้า</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../design/" class="nav-link <?php echo isActive('design') ?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>ข้อมูลแบบชุด</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../branch/" class="nav-link <?php echo isActive('branch') ?>">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>ข้อมูลชำระเงิน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../activity_type/" class="nav-link <?php echo isActive('activity_type') ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>ข้อมูลสั่งตัดชุด</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../activity_type/" class="nav-link <?php echo isActive('activity_type') ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>ข้อมูลพนักงาน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../activity_type/" class="nav-link <?php echo isActive('activity_type') ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>ข้อมูลการทำงานของช่าง</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../activity_type/" class="nav-link <?php echo isActive('activity_type') ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>ข้อมูลเงินเดือน</p>
                    </a>
                </li>
                <Br>

                <li class="nav-header">บัญชีของเรา</li>
                <li class="nav-item">
                    <a href="../logout.php" id="logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>ออกจากระบบ</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

