<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'พนักงาน') {
    header('Location: ../login.php');
    exit();
}

$ord_id = $_GET['id'];

$sql = "SELECT * FROM order_sewing WHERE ord_id = :ord_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':ord_id', $ord_id, PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
// print_r($result);
// return;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการข้อมูลสั่งตัดชุด</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/PLogo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
                            <div class="card shadow">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-user-cog"></i>
                                        แก้ไขข้อมูลสั่งตัดชุด
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
                                                    <label for="ord_id">รหัสสั่งตัดชุด</label>
                                                    <input type="text" class="form-control" name="ord_id" id="ord_id" placeholder="รหัสสั่งตัดชุด" value="<?= $result['ord_id']; ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="detail_measure">รายละเอียดวัดตัว</label>
                                                    <input type="text" class="form-control" name="detail_measure" id="detail_measure" placeholder="รายละเอียดวัดตัว" value="<?= $result['detail_measure']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ord_size">ขนาดผ้า/เมตร</label>
                                                    <input type="text" class="form-control" name="ord_size" id="ord_size" placeholder="ขนาดผ้า/เมตร" value="<?= $result['ord_size']; ?>" required>
                                                </div>


                                                <div class="form-group">
                                                    <label for="detailmore">รายละเอียดเพิ่มเติม </label>
                                                    <input type="text" class="form-control" name="detailmore" id="detailmore" placeholder="รายละเอียดราคาเพิ่มเติม	" value="<?= $result['detailmore']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ord_date">วันที่สั่งตัด</label>
                                                    <input type="date" class="form-control" name="ord_date" id="ord_date" value="<?= $result['ord_date']; ?>" required>
                                                </div>
                                                <div class="col-md-6 px-1 px-md-5">
                                                    <!--วนลูปค่าจากฐานข้อมูลมาแสดง -->
                                                    <div class="form-group">
                                                        <label for="detail">แบบชุด</label>
                                                        <select class="form-control" name="detail" id="detail">
                                                            <option value disabled selected>เลือกแบบชุด</option>
                                                            <?php
                                                            $designQuery = $conn->query("SELECT * FROM design");
                                                            $designs = $designQuery->fetchAll(PDO::FETCH_ASSOC);
                                                            foreach ($designs as $design) {
                                                                echo "<option value='" . $design['design_id'] . "'>" . $design['detail'] . "</option>";
                                                            }
                                                            ?>
                                                        </select><br>
                                                        <div id="designDetails"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="order_price">ค่าตัดชุด/บาท</label>
                                                        <input type="text" class="form-control" name="order_price" id="order_price" placeholder="ค่าตัดชุด" value="<?= $result['order_price']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="price_detailmore">ราคาเพิ่มเติม </label>
                                                        <input type="text" class="form-control" name="price_detailmore" id="price_detailmore" placeholder="ราคาเพิ่มเติม " value="<?= $result['price_detailmore']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="total_price">ราคารวม:</label>
                                                        <input type="text" class="form-control" name="total_price" id="total_price" readonly>
                                                    </div>

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
        $('#order_price, #price_detailmore').on('input', function() {
            calculateTotalPrice();
        });

        function calculateTotalPrice() {
            var orderPrice = parseFloat($('#order_price').val()) || 0;
            var priceDetailmore = parseFloat($('#price_detailmore').val()) || 0;
            var startingPrice = parseFloat($('#starting_price').val()) || 0;
            var totalPrice = orderPrice + priceDetailmore;

            $('#total_price').val(totalPrice.toFixed(2) + ' บาท');
        }
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            var orderId = urlParams.get('id');

            if (orderId) {
                $.ajax({
                    type: 'POST',
                    url: '../../service/order_sewing/get_design_id.php',
                    data: {
                        order_id: orderId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            var designId = response.data.design_id;

                            $.ajax({
                                type: 'POST',
                                url: '../../service/order_sewing/get_design_details.php',
                                data: {
                                    design_id: designId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status) {
                                        var designDetailsHtml = '<img src="../../assets/images/' + response.data.design_image + '" alt="Design Image" class="img-fluid" style="width: 150px; height: auto;" />';

                                        designDetailsHtml += '<div class="frame">';
                                        designDetailsHtml += '    <label for="design_id">รหัสแบบชุด:</label>';
                                        designDetailsHtml += '    <input type="text" class="form-control" id="design_id" name="design_id" value="' + response.data.design_id + '" readonly>';
                                        designDetailsHtml += '</div>';

                                        designDetailsHtml += '<div class="frame">';
                                        designDetailsHtml += '    <label for="starting_price">ราคาเริ่มต้น:</label>';
                                        designDetailsHtml += '    <input type="text" class="form-control" id="starting_price" value="' + response.data.starting_price + ' บาท" readonly>';
                                        designDetailsHtml += '</div>';

                                        designDetailsHtml += '<div class="frame">';
                                        designDetailsHtml += '    <label for="period">ระยะเวลาตัดชุด:</label>';
                                        designDetailsHtml += '    <input type="text" class="form-control" id="period" value="' + response.data.period + '" readonly>';
                                        designDetailsHtml += '</div>';

                                        $('#designDetails').html(designDetailsHtml);
                                        calculateTotalPrice();
                                    } else {
                                        // Handle error response
                                        Swal.fire({
                                            text: response.message,
                                            icon: 'error',
                                            confirmButtonText: 'ตกลง',
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        text: 'เกิดข้อผิดพลาดในการเรียกข้อมูล: ' + error,
                                        icon: 'error',
                                        confirmButtonText: 'ตกลง',
                                    });
                                }
                            });

                        } else {
                            // Handle error response for getting designId based on order_id
                            Swal.fire({
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'ตกลง',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            text: 'เกิดข้อผิดพลาดในการเรียกข้อมูล: ' + error,
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                        });
                    }
                });
            } else {
                // Clear designDetails if no orderId is present in the URL
                $('#designDetails').html('');
            }
        });


        // Your existing JavaScript code here
        $('#detail').on('change', function() {
            var designId = $(this).val();
            if (designId) {
                // AJAX request to get design details
                $.ajax({
                    type: 'POST',
                    url: '../../service/order_sewing/get_design_details.php',
                    data: {
                        design_id: designId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            var designDetailsHtml = '<img src="../../assets/images/' + response.data.design_image + '" alt="Design Image" class="img-fluid" style="width: 150px; height: auto;" />';
                            // ใส่ข้อมูลราคาในกรอบแบบ form-group
                            designDetailsHtml += '<div class="frame">';
                            designDetailsHtml += '    <label for="design_id">รหัสแบบชุด:</label>';
                            designDetailsHtml += '    <input type="text" class="form-control" id="design_id" name="design_id" value="' + response.data.design_id + '" readonly>';
                            designDetailsHtml += '</div>';

                            designDetailsHtml += '<div class="frame">';
                            designDetailsHtml += '    <label for="starting_price">ราคาเริ่มต้น:</label>';
                            designDetailsHtml += '    <input type="text" class="form-control" id="starting_price" value="' + response.data.starting_price + ' บาท" readonly>';
                            designDetailsHtml += '</div>';

                            designDetailsHtml += '<div class="frame">';
                            designDetailsHtml += '    <label for="period">ระยะเวลาตัดชุด:</label>';
                            designDetailsHtml += '    <input name="period" type="text" class="form-control" id="period" value="' + response.data.period + '" readonly>';
                            designDetailsHtml += '</div>';
                            $('#designDetails').html(designDetailsHtml);
                            calculateTotalPrice();
                        } else {
                            // Handle error response
                            Swal.fire({
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'ตกลง',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            text: 'เกิดข้อผิดพลาดในการเรียกข้อมูล: ' + error,
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                        });
                    }
                });
            } else {
                // Clear designDetails if no design is selected
                $('#designDetails').html('');
            }
        });

        $(function() {
            $('#formData').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../../service/order_sewing/update.php',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        detail_measure: $('#detail_measure').val(),
                        ord_size: $('#ord_size').val(),
                        order_price: $('#order_price').val(),
                        detailmore: $('#detailmore').val(),
                        price_detailmore: $('#price_detailmore').val(),
                        ord_date: $('#ord_date').val(),
                        ord_status: $('#ord_status').val(),
                        emp_id: $('#emp_id').val(),
                        design_id: $('#design_id').val(),
                        ord_id: $('#ord_id').val(),
                        total_price: $('#total_price').val(),
                        period_date: $('#period').val()
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