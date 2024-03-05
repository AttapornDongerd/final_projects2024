<?php 
    require_once('../authen.php'); 
    if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'พนักงาน') {
        header('Location: ../login.php');
        exit();
    }
        // // ตรวจสอบว่า ID ของพนักงานที่กำลังใช้งานไม่เท่ากับ ID ของผู้ใช้ที่กำลังเข้าถึงหน้านี้
        // if (isset($_SESSION['EMP_ID']) && isset($_GET['emp_id']) && $_SESSION['EMP_ID'] !== $_GET['emp_id']) {
        //     header('Location: ../access-denied.php'); // เปลี่ยนเส้นทางไปที่หน้าที่ต้องการให้พนักงานเห็น
        //     exit();
        // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home-Board</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/PLogo.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include_once('../includes/sidebar_admin.php') ?>
    <div class="content-wrapper pt-3">
        


    
    </div>
</div>


<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>
<script src="../../plugins/chart.js/Chart.min.js"></script>
<script src="../../plugins/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="../../assets/js/pages/dashboard.js"></script>
</body>
</html>
