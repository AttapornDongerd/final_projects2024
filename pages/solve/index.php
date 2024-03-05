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
    <title>จัดการข้อมูลการแก้ชุด</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/PLogo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
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
                                        ข้อมูลการแก้ชุด
                                    </h4>                       
                                </div>
                                <div class="card-body">
                                    <table id="logs" class="table table-hover" width="100%">
                                    </table>
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

    <script>
        $(function() {
            $.ajax({
                type: "GET",
                url: "../../service/solve/index.php"
            }).done(function(data) {
                let tableData = []
                data.response.forEach(function(item, index) {

                    let statusBadge;
                    if (item.ord_status_check === 1) {
                        statusBadge = `<span class="badge badge-info">กำลังตัด</span>`;
                    } else if (item.ord_status_check === 2) {
                        statusBadge = `<span class="badge badge-success">เสร็จสิ้น</span>`;
                    } else if (item.ord_status_check === 3) {
                        statusBadge = `<span class="badge badge-success">กำลังแก้</span>`;
                    } else {
                        statusBadge = item.ord_status_check; 
                    }

                    tableData.push([
                        // ++index,
                        item.ord_id,
                        item.emp_id,
                        item.queue_datefinish,
                        statusBadge,
                        `<div class="btn-group" role="group">
                            <a href="form-edit.php?ord_id=${item.ord_id}" type="button" class="btn btn-warning text-white mr-2">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>                           
                        </div>`
                        ,
                    ])
                })
                initDataTables(tableData)
            }).fail(function() {
                Swal.fire({
                    text: 'ไม่สามารถเรียกดูข้อมูลได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                }).then(function() {
                    location.assign('../dashboard_admin')
                })
            })

            function initDataTables(tableData) {
                $('#logs').DataTable({
                    data: tableData,
                    columns: [{
                            title: "รหัสสั่งตัดชุด",
                            className: "align-middle"
                        },
                        {
                            title: "รหัสพนักงาน",
                            className: "align-middle"
                        },
                        {
                            title: "วันที่แก้เสร็จสิ้น",
                            className: "align-middle"
                        },
                        {
                            title: "สถานะ",
                            className: "align-middle"
                        },
                        {
                            title: "Actions",
                            className: "align-middle"
                        },                       
                    ],

                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data()
                                    return '555: ' + data[1]
                                }
                            }),
                            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                                tableClass: 'table'
                            })
                        }
                    },

                })
            }

        })
    </script>
</body>

</html>