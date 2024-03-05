<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'พนักงาน') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['ord_id'])) {
    echo "Error: Missing 'id' parameter.";
    exit();
}

$ord_id = $_GET['ord_id'];

$sql = "SELECT order_sewing.*, employees.emp_id, queu_e.queue_datefinish,  customer.cus_name, order_sewing.detail_measure
FROM order_sewing 
LEFT JOIN employees ON order_sewing.emp_id = employees.emp_id
LEFT JOIN customer ON order_sewing.cus_id = customer.cus_id
LEFT JOIN queu_e ON order_sewing.ord_id = queu_e.ord_id
WHERE order_sewing.ord_id = :ord_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':ord_id', $ord_id, PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>จัดการข้อมูลแก้ชุด</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/PLogo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

</head>



<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar_admin.php') ?>
        <div class="content-wrapper pt-3">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-shopping-cart"></i>
                                        จัดการข้อมูลแก้ชุด
                                    </h4>
                                    <div class="card-header d-flex justify-content-end">
                                        <a href="index.php" class="btn btn-success mt-3">
                                            กลับหน้าหลัก
                                        </a>
                                    </div>
                                </div>
                                <form id="formData">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="ord_id">รหัสสั่งตัดชุด</label>
                                                <input type="text" class="form-control" name="ord_id" id="ord_id" placeholder="รหัสสั่งตัดชุด" value="<?= $ord_id; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="cus_name">ชื่อลูกค้า</label>
                                                <input type="text" class="form-control" name="cus_name" id="cus_name" value="<?= $result['cus_name']; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="detail_measure">รายละเอียดการวัด</label>
                                                <textarea class="form-control" name="detail_measure" id="detail_measure" readonly><?= $result['detail_measure']; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="solv_detail">รายละเอียดแก้ชุด</label>
                                                <input type="text" class="form-control" name="solv_detail" id="solv_detail" placeholder="รายละเอียดแก้ชุด" required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="queue_datefinish">วันที่แก้เสร็จ</label>
                                                <input type="date" class="form-control" name="queue_datefinish" id="queue_datefinish" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-block mx-auto w-75" name="submit">บันทึกข้อมูล</button>
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
    <script src="../../plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>

    <script>
        // $(function() {
        //     $('#formData').on('submit', function(e) {
        //         e.preventDefault();
        //         var formData = {
        //             ord_id: $('#ord_id').val(),
        //             solv_detail: $('#solv_detail').val(),
        //             queue_datefinish: $('#queue_datefinish').val(),
        //             cus_name: $('#cus_name').val(),
        //             detail_measure: $('#detail_measure').val()
        //         };
        //         $.ajax({
        //             type: 'POST',
        //             url: '../../service/solve/update.php',
        //             data: JSON.stringify(formData), // Convert formData to JSON
        //             contentType: 'application/json', // Set content type to JSON
        //             dataType: 'json',
        //             success: function(resp) {
        //                 if (resp.status) {
        //                     Swal.fire({
        //                         text: 'แก้ไขข้อมูลเรียบร้อย',
        //                         icon: 'success',
        //                         confirmButtonText: 'ตกลง',
        //                     }).then((result) => {
        //                         location.assign('./');
        //                     });
        //                 } else {
        //                     Swal.fire({
        //                         text: resp.message,
        //                         icon: 'error',
        //                         confirmButtonText: 'ตกลง',
        //                     });
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 Swal.fire({
        //                     text: 'เกิดข้อผิดพลาดในการส่งข้อมูล: ' + error,
        //                     icon: 'error',
        //                     confirmButtonText: 'ตกลง',
        //                 });
        //             }
        //         });
        //     });
        // });

        $(function() {
            $('#formData').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../../service/solve/update.php',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        ord_id: $('#ord_id').val(),
                        solv_detail: $('#solv_detail').val(),
                        queue_datefinish: $('#queue_datefinish').val(),
                        cus_name: $('#cus_name').val(),
                        detail_measure: $('#detail_measure').val()
                    }),
                    success: function(resp) {
                    if (resp.status) {
                        Swal.fire({
                            text: 'อัพเดทเรียบร้อย',
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