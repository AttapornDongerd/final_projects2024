<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'พนักงาน') {
    header('Location: ../login.php');
    exit();
}
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
                                        เพิ่มข้อมูลสั่งตัดชุด
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
                                                <?php
                                                $randomNumber = mt_rand(0, 9999); 
                                                $randomNumber = str_pad($randomNumber, 4, '0', STR_PAD_LEFT);   
                                                $ord_id = 'OR' . $randomNumber;                             
                                                ?>
                                                <div class="form-group">
                                                    <label for="ord_id">รหัสสั่งตัดชุด</label>
                                                    <input type="text" class="form-control" name="ord_id" value="<?php echo $ord_id  ?>" id="ord_id" placeholder="รหัสสั่งตัดชุด" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cus_id">ชื่อลูกค้า</label>
                                                    <select class="form-control" name="cus_id" id="cus_id" required>
                                                        <option value disabled selected>ชื่อลูกค้า</option>
                                                        <?php
                                                        $customerQuery = $conn->query("SELECT * FROM customer");
                                                        $customer = $customerQuery->fetchAll(PDO::FETCH_ASSOC);
                                                        foreach ($customer as $customers) {
                                                            echo "<option value='" . $customers['cus_id'] . "'>" . $customers['cus_name'] . "</option>";
                                                        }
                                                        ?>
                                                    </select><br>
                                                </div>
                                                <div class="form-group">
                                                    <label for="detail_measure">รายละเอียดวัดตัว</label>
                                                    <input type="text" class="form-control" name="detail_measure" id="detail_measure" placeholder="รายละเอียดวัดตัว" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ord_size">ขนาดผ้า/เมตร</label>
                                                    <input type="text" class="form-control" name="ord_size" id="ord_size" placeholder="ขนาดผ้า" required>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="detailmore">รายละเอียดเพิ่มเติม </label>
                                                    <input type="text" class="form-control" name="detailmore" id="detailmore" placeholder="รายละเอียดเพิ่มเติม	" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ord_date">วันที่สั่งตัด</label>
                                                    <input type="date" class="form-control" name="ord_date" id="ord_date" value="<?= isset($result['ord_date']) ? $result['ord_date'] : ''; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 px-1 px-md-5">
                                                <!--วนลูปค่าจากฐานข้อมูลมาแสดง -->
                                                <div class="form-group">
                                                    <label for="detail">รายละเอียดผ้า</label>
                                                    <select class="form-control" name="detail" id="detail" required>
                                                        <option value disabled selected>เลือกผ้า</option>
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
                                                    <input type="text" class="form-control" name="order_price" id="order_price" placeholder="ค่าตัดชุด" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price_detailmore">ราคาเพิ่มเติม </label>
                                                    <input type="text" class="form-control" name="price_detailmore" id="price_detailmore" placeholder="ราคาเพิ่มเติม" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="total_price">ราคารวม:</label>
                                                    <input type="text" class="form-control" name="total_price" id="total_price" readonly>
                                                </div>

                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary btn-block mx-auto w-20" name="submit">บันทึกข้อมูล</button>
                                            </div>
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
        // Your existing JavaScript code here
        // Your existing JavaScript code here
        $('#order_price, #price_detailmore').on('input', function() {
            calculateTotalPrice();
        });

        $('#detail').on('change', function() {
            // Existing code for fetching design details
            // ...

            // After fetching design details, calculate total price
            calculateTotalPrice();
        });

        function calculateTotalPrice() {
            var orderPrice = parseFloat($('#order_price').val()) || 0;
            var priceDetailmore = parseFloat($('#price_detailmore').val()) || 0;
            var startingPrice = parseFloat($('#starting_price').val()) || 0;
            var totalPrice = orderPrice + priceDetailmore;

            $('#total_price').val(totalPrice.toFixed(2) + ' บาท');
        }


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
                            designDetailsHtml += '    <input type="text" name="period_date" class="form-control" id="period" value="' + response.data.period + '" readonly>';
                            designDetailsHtml += '</div>';
                            $('#designDetails').html(designDetailsHtml);
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

        $('#formData').on('submit', function(e) {
            e.preventDefault();
            var formData = $('#formData').serialize();
            $.ajax({
                type: 'POST',
                url: '../../service/order_sewing/create.php',
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
                        text: 'เกิดข้อผิดพลาดในการส่งข้อมูล: ' + error,
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                    });
                }
            });
        });
    </script>

</body>

</html>

</body>

</html>