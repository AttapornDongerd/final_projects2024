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
    <title>จัดการข้อมูลการตัดชุด</title>
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
                                        ข้อมูลการตัดชุด
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
                url: "../../service/tailored_suit/index.php"
            }).done(function(data) {
                let tableData = []
                data.response.forEach(function(item, index) {

                    let statusBadge;
                    if (item.ord_status_check === 1) {
                        statusBadge = `<span class="badge badge-info">กำลังตัด</span>`;
                    } else if (item.ord_status_check === 2) {
                        statusBadge = `<span class="badge badge-success">เสร็จสิ้น</span>`;
                    } else if (item.ord_status_check === 3) {
                        statusBadge = `<span class="badge badge-secondary">กำลังแก้</span>`;
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
                            <a href="info.php?ord_id=${item.ord_id}" type="button" class="btn btn-info">
                                <i class="far fa-edit"></i> ดูเพิ่มเติม
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
                            title: "วันที่เสร็จสิ้น",
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
                        // {
                        //     title: "นัดรับ",
                        //     className: "align-middle"
                        // }
                    ],

                    initComplete: function() {
                        $(document).on('click', '#delete', function() {
                            let ord_id = $(this).data('id');
                            let index = $(this).data('index');
                            Swal.fire({
                                text: "คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'ใช่! ลบเลย',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        type: "DELETE",
                                        url: "../../service/tailored_suit/delete.php",
                                        data: JSON.stringify({
                                            ord_id: ord_id
                                        }),
                                        contentType: "application/json; charset=utf-8",
                                        dataType: "json"
                                    }).done(function(data) {
                                        Swal.fire({
                                            text: 'รายการของคุณถูกลบเรียบร้อย',
                                            icon: 'success',
                                            confirmButtonText: 'ตกลง',
                                        }).then((result) => {
                                            location.reload();
                                        })
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        console.log("AJAX Error: " + textStatus + ' - ' + errorThrown);
                                    })
                                }
                            })
                        })
                    },
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