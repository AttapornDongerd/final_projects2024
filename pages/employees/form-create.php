<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'เจ้าของร้าน') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการข้อมูลพนักงาน</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/PLogo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar_superadmin.php') ?>
        <div class="content-wrapper pt-3">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-user-cog"></i>
                                        เพิ่มข้อมูลพนักงาน
                                    </h4>
                                    <div class="card-header d-flex justify-content-end">
                                        <a href="index.php" class="btn btn-success mt-3">
                                            กลับหน้าหลัก
                                        </a>
                                    </div>
                                </div>
                                <form id="formData">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 px-1 px-md-5">
                                                <div class="form-group">
                                                    <label for="emp_id ">รหัสผู้ใช้งาน *</label>
                                                    <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="รหัสผู้ใช้งาน" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emp_name">ชื่อ-นามสกุล</label>
                                                    <input type="text" class="form-control" name="emp_name" id="emp_name" placeholder="ชื่อ-นามสกุล" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emp_user">ชื่อผู้ใช้งาน *</label>
                                                    <input type="text" class="form-control" name="emp_user" id="emp_user" placeholder="ชื่อผู้ใช้งาน" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="emp_pass">รหัสผ่าน</label>
                                                    <input type="password" class="form-control" name="emp_pass" id="emp_pass" placeholder="รหัสผ่าน" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emp_tel">เบอร์โทร</label>
                                                    <input type="tel" class="form-control" name="emp_tel" id="emp_tel" placeholder="เบอร์โทร" pattern="[0-9]{10}" title="Please enter a 10-digit phone number" maxlength="10" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">สิทธิ์การใช้งาน</label>
                                                    <select class="form-control" name="status" id="status" required>
                                                        <option value disabled selected>ตำแหน่ง</option>
                                                        <option value="เจ้าของร้าน">เจ้าของร้าน</option>
                                                        <option value="พนักงาน">พนักงาน</option>
                                                    </select>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>

    <script>
        $(function() {
            $('#formData').on('submit', function(e) {
                e.preventDefault();
                var formData = $('#formData').serialize();
                $.ajax({
                    type: 'POST',
                    url: '../../service/employees/create.php',
                    data: formData,
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.status) {
                            Swal.fire({
                                text: 'เพิ่มข้อมูลเรียบร้อย',
                                icon: 'success',
                                confirmButtonText: 'ตกลง',
                            }).then((result) => {
                                location.assign('./');
                            });
                        } else {
                            Swal.fire({
                                text: resp.message,
                                icon: 'error',
                                confirmButtonText: 'ตกลง',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            text: 'ไม่สามารถเพิ่มข้อมูลได้เนื่องจากรหัสผู้ใช้งานหรือชื่อผู้ใช้งานซ้ำ: ' + error,
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                        });
                    }
                });
            });

        });
    </script>

</body>

</html>