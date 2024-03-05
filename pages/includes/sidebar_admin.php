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
            <a href="../dashboard_2/">
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
                <a href="#" class="d-block"> <?php echo $_SESSION['EMP_NAME']?> </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="../dashboard_admin/" class="nav-link <?php echo isActive('dashboard') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>หน้าหลัก</p>
                    </a>
                </li>
                <br>

                <li class="nav-header">จัดการข้อมูล</li>
                
                <li class="nav-item">
                    <a href="../order_sewing/" class="nav-link <?php echo isActive('order_sewing') ?>">
                        <i class="nav-icon fas fa-puzzle-piece"></i>
                        <p>ข้อมูลสั่งตัดชุด</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../queu_e/" class="nav-link <?php echo isActive('queu_e') ?>">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>ข้อมูลคิวช่าง</p>
                    </a>
                </li> <br>
                <li class="nav-item">
                    <a href="../tailored_suit/" class="nav-link <?php echo isActive('tailored_suit') ?>">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>ข้อมูลการตัดชุด</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../solve/" class="nav-link <?php echo isActive('solve') ?>">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>ข้อมูลการแก้ชุด</p>
                    </a>
                </li>
                

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

