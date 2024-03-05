<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'พนักงาน') {
    header('Location: ../../login.php');
    exit();
}

if (!isset($_GET['ord_id'])) {
    echo "ord_id is missing in the URL.";
    exit();
}

$ord_id = $_GET['ord_id'];
$stmt = $conn->prepare("SELECT order_sewing.*, employees.emp_id, employees.emp_name, design.detail, design.design_image, customer.cus_name, design.period, queu_e.queue_datefinish
 FROM order_sewing 
                                    LEFT JOIN employees ON order_sewing.emp_id = employees.emp_id
                                    LEFT JOIN design ON order_sewing.design_id = design.design_id
                                    LEFT JOIN customer ON order_sewing.cus_id = customer.cus_id
                                    LEFT JOIN queu_e ON order_sewing.ord_id = queu_e.ord_id
                                    WHERE order_sewing.ord_id = :ord_id");
$stmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "Order Sewing not found.";
    exit();
}

$order_sewingInfo = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลสั่งตัดชุด</title>
    <!-- stylesheet -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0mm;
        }

        body {
            margin: 20mm;
            font-family: 'Kanit', sans-serif;
            /* เปลี่ยนเป็น font ที่คุณใช้ในหน้าที่แสดงข้อมูล */
        }

        /* เพิ่ม CSS อื่น ๆ ที่ใช้ในหน้าที่แสดงข้อมูล */
    </style>
</head>


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar_admin.php') ?>
        <div class="content-wrapper pt-4">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="nav-icon fas fa-puzzle-piece"></i>
                                        ข้อมูลสั่งตัดชุด
                                    </h4>
                                    <div class="card-header d-flex justify-content-end">
                                        <button class="btn btn-info my-3" onclick="goBack()">
                                            <i class="fas fa-arrow-left"></i>
                                            กลับหน้าหลัก
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card shadow-sm">
                                                <div class="card-header pt-4">
                                                    <h3 class="card-title">
                                                        <i class="fas fa-bookmark"></i>
                                                        รายละเอียดเพิ่มเติม
                                                    </h3>
                                                </div>
                                                <!-- ------------------------------------------------------------------------------------------------------------------------------------------- -->

                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">รหัสสั่งตัดชุด</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['ord_id']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center"> ชื่อลูกค้า :</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['cus_name']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">รายละเอียดวัดตัว</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['detail_measure']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">ขนาดผ้า/เมตร</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['ord_size']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">รายละเอียดเพิ่มเติม </th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['detailmore']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center"> รายละเอียดผ้า </th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['detail']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center"> แบบชุด </th>
                                                            <td class="col-xl-9">
                                                                <!-- เพิ่ม tag <img> เพื่อแสดงรูปภาพ -->
                                                                <img src="../../assets/images/<?= $order_sewingInfo['design_image']; ?>" alt="Design Image" class="img-fluid" style="width: 150px; height: auto;" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">ระยะเวลาตัดชุด </th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['period']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">วันที่สั่งตัด </th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['ord_date']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">วันที่เสร็จสิ้น :</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['queue_datefinish']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">ค่าตัดชุด</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['order_price']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">ราคาเพิ่มเติม</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['price_detailmore']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xl-4 text-muted text-center">ราคารวม</th>
                                                            <td class="col-xl-9"><?= $order_sewingInfo['total_price']; ?></td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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


    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
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
    </script>


    <script>
        function submitReport() {
            const reportContent = document.getElementById('reportInput').value;
            console.log('Report Content:', reportContent);
            Swal.fire({
                text: 'รายงานถูกส่งเรียบร้อย',
                icon: 'success',
                confirmButtonText: 'ตกลง',
            });
        }

        function printDocument() {
            window.print();
        }
    </script>

    <script>
        function goBack() {
            window.history.back();
        }


        $(function() {
            $.ajax({
                type: "GET",
                url: "../../service/order_sewing/info.php"
            }).done(function(data) {
                let tableData = []
                data.response.forEach(function(item, index) {
                    tableData.push([
                        item['ord_id'],
                        item['detail_measure'],
                        item['ord_size'],
                        item['order_price'],
                        item['detailmore'],
                        item['emp_id'],
                        item['design_id'],
                        item['cus_id']
                    ])
                })
                initDataTables(tableData)
            }).fail(function() {
                Swal.fire({
                    text: 'ไม่สามารถเรียกดูข้อมูลได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                }).then(function() {
                    location.assign('../dashboard_2')
                })
            })

            function initDataTables(tableData) {
                $('#logs').DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false,
                    data: tableData,
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    return 'กิจกรรม'
                                }
                            }),
                            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                                tableClass: 'table'
                            })
                        }
                    },
                    language: {
                        "lengthMenu": "แสดงข้อมูล MENU แถว",
                        "zeroRecords": "ไม่พบข้อมูลที่ต้องการ",
                        "info": "แสดงหน้า PAGE จาก PAGES",
                        "infoEmpty": "ไม่พบข้อมูลที่ต้องการ",
                        "infoFiltered": "(filtered from MAX total records)",
                        "search": 'ค้นหา',
                        "paginate": {
                            "previous": "ก่อนหน้านี้",
                            "next": "หน้าต่อไป"
                        }
                    },
                });
            }
        });
        $(function() {
            $('#pdf-order_sewing').click(function() {
                var ord_id = '<?= $order_sewingInfo['ord_id']; ?>';
                window.open('pdf-order_sewing.php?ord_id=' + ord_id, '_blank');
            });
        });
    </script>
</body>

</html>