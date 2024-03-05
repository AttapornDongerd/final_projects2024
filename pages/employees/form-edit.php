<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'เจ้าของร้าน') {
    header('Location: ../login.php');
    exit();
}

$emp_id  = $_GET['id'];

$sql = "SELECT * FROM employees WHERE emp_id = :emp_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_STR);
$stmt->execute();

if ($stmt) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
// return;
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
                                        แก้ไขข้อมูลผู้ดูแล
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
                                                    <label for="emp_id">รหัสผู้ใช้งาน *</label>
                                                    <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="รหัสผู้ใช้งาน" value="<?= $result['emp_id']; ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emp_name">ชื่อ-นามสกุล</label>
                                                    <input type="text" class="form-control" name="emp_name" id="emp_name" placeholder="ชื่อ-นามสกุล" value="<?= $result['emp_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emp_user">ชื่อผู้ใช้งาน</label>
                                                    <input type="text" class="form-control" name="emp_user" id="emp_user" placeholder="ชื่อผู้ใช้งาน" value="<?= $result['emp_user']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emp_tel">เบอร์โทร</label>
                                                    <input type="tel" class="form-control" name="emp_tel" id="emp_tel" placeholder="เบอร์โทร" value="<?= $result['emp_tel']; ?>" pattern="[0-9]{10}" title="Please enter a 10-digit phone number" maxlength="10" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">สิทธิ์การใช้งาน</label>
                                                    <select class="form-control" name="status" id="status" required>
                                                        <option value="" disabled>กำหนดสิทธิ์</option>
                                                        <option value="เจ้าของร้าน" <?= ($result['status'] === 'เจ้าของร้าน') ? 'selected' : ''; ?>>เจ้าของร้าน</option>
                                                        <option value="พนักงาน" <?= ($result['status'] === 'พนักงาน') ? 'selected' : ''; ?>>พนักงาน</option>
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
    <!-- SCRIPTS -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>

    <script>
        $(function() {
            $('#formData').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../../service/employees/update.php',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        emp_name: $('#emp_name').val(),
                        emp_user: $('#emp_user').val(),
                        emp_tel: $('#emp_tel').val(),
                        status: $('#status').val(),
                        emp_id: $('#emp_id').val()
                    })
                }).done(function(resp) {
                    Swal.fire({
                        text: 'อัพเดทข้อมูลเรียบร้อย',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                    }).then((result) => {
                        location.assign('./');
                    });
                });
            });
        });
    </script>

</body>

</html>
<?php
    } else {
        echo "Error fetching data from the database.";
    }
} else {
    echo "Error executing the SQL query.";
}
?>