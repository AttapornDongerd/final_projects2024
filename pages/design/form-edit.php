<?php
require_once('../authen.php');
if (!isset($_SESSION['EMP_STATUS']) || $_SESSION['EMP_STATUS'] !== 'เจ้าของร้าน') {
    header('Location: ../login.php');
    exit();
}

$design_id = $_GET['design_id'];

$sql = "SELECT * FROM design WHERE design_id = :design_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':design_id', $design_id, PDO::PARAM_STR);
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
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>จัดการข้อมูลแบบชุด</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/PLogo.png">
    <!-- stylesheet -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar_superadmin.php') ?>
        <div class="content-wrapper pt-3">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-shopping-cart"></i>
                                        จัดการข้อมูลแบบชุด
                                    </h4>
                                    <div class="card-header d-flex justify-content-end">
                                        <a href="index.php" class="btn btn-success mt-3">
                                            กลับหน้าหลัก
                                        </a>
                                    </div>
                                </div>
                                <form id="formData" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-group col-md-5">
                                                <label for="design_id">รหัสแบบชุด *</label>
                                                <input type="text" class="form-control" name="design_id" id="design_id" placeholder="รหัสแบบชุด" value="<?= $result['design_id']; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="detail">รายละเอียดผ้า</label>
                                                <select class="form-control" name="detail" id="detail" required>
                                                    <option value="" disabled>รายละเอียดผ้า</option>
                                                    <option value="ผ้าไหม" <?= ($result['detail'] === 'ผ้าไหม') ? 'selected' : ''; ?>>ผ้าไหม</option>
                                                    <option value="ผ้าฝ้าย" <?= ($result['detail'] === 'ผ้าฝ้าย') ? 'selected' : ''; ?>>ผ้าฝ้าย</option>
                                                    <option value="ผ้ามัดหมี่" <?= ($result['detail'] === 'ผ้ามัดหมี่') ? 'selected' : ''; ?>>ผ้ามัดหมี่</option>
                                                    <option value="ผ้าลายน้ำไหล" <?= ($result['detail'] === 'ผ้าลายน้ำไหล') ? 'selected' : ''; ?>>ผ้าลายน้ำไหล</option>
                                                    <option value="ผ้าจก" <?= ($result['detail'] === 'ผ้าจก') ? 'selected' : ''; ?>>ผ้าจก</option>
                                                    <option value="ผ้าแพรวา" <?= ($result['detail'] === 'ผ้าแพรวา') ? 'selected' : ''; ?>>ผ้าแพรวา</option>
                                                    <option value="ผ้าม่อฮ่อม" <?= ($result['detail'] === 'ผ้าม่อฮ่อม') ? 'selected' : ''; ?>>ผ้าม่อฮ่อม</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-5">
                                                <label for="design_image">รูปภาพ</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="design_image" name="design_image" onchange="updateFileName()">
                                                    <label class="custom-file-label" for="design_image" id="fileLabel"><?= $result['design_image']; ?></label>

                                                </div>
                                                <?php if ($result['design_image']) : ?>
                                                    <img src="../../assets/images/<?= $result['design_image']; ?>" id="img_output_01" alt="Uploaded Image" class="mt-2" style="max-width: 50%; height: auto;">
                                                <?php endif; ?>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <label for="starting_price">ราคา</label>
                                                <input type="text" class="form-control" name="starting_price" id="starting_price" placeholder="ราคา" value="<?= $result['starting_price']; ?>">
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="period">ระยะเวลาตัดชุด</label>
                                                <input type="text" class="form-control" name="period" id="period" placeholder="ระยะเวลาตัดชุด" value="<?= $result['period']; ?>">
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

    <!-- scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>

    <script>
        function updateFileName() {
            var input = document.getElementById('design_image');
            var label = document.getElementById('fileLabel');
            var image = document.getElementById('img_output_01');

            if (input.files.length > 0) {
                var fileName = input.files[0].name;
                label.innerHTML = fileName;
                var imageURL = URL.createObjectURL(input.files[0]);
                image.src = imageURL;
            } else {
                label.innerHTML = "เลือกไฟล์";
            }
        }

        $(function() {

            $('#formData').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var fileInput = $('#design_image')[0];
                var file = fileInput.files[0];
                var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (file && allowedTypes.indexOf(file.type) === -1) {
                    // Display error message if file type is not allowed
                    alert('อัปโหลดไฟล์รูปเฉพาะ JPG, JPEG, PNG, และ GIF เท่านั้น');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '../../service/design/update.php',
                    processData: false,
                    contentType: false,
                    data: formData
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
