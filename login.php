<?php

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/PLogo.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
        body {
            background-color: #ADD8E6;
        }
        .bg {
            background-color: #ADD8E6;
            height: 100px;
        }
    </style>
</head>

<body>
  <header class="bg"></header>
  <section class="d-flex align-items-center min-vh-100">
    <div class="container">
      <div class="row justify-content-center">
        <section class="col-lg-6">
          <div class="card shadow p-3 p-md-4">
            <h1 class="text-center text-primary font-weight-bold">Praewa Boutique</h1>
            <h4 class="text-center">Login</h4>
            <div class="card-body">
              <form id="formLogin" method="POST" action="service/auth/login.php">
                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text px-2">ชื่อผู้ใช้งาน</div>
                    </div>
                    <input type="text" class="form-control" name="emp_user" placeholder="ชื่อผู้ใช้" required>
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text px-3">รหัสผ่าน</div>
                    </div>
                    <input type="password" class="form-control" name="emp_pass" placeholder="รหัสผ่าน" required>
                  </div>
                </div>
                <button type="submit" name="btn_login" class="btn btn-primary btn-block"> เข้าสู่ระบบ</button>
              </form>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="plugins/toastr/toastr.min.js"></script>
  <script>
    $(function() {
      $("#formLogin").submit(function(e) {
        e.preventDefault()
        $.ajax({
          type: "POST",
          url: "service/auth/login.php",
          data: $(this).serialize()
        }).done(function(data) {
          console.log(data.status)
          toastr.success('เข้าสู่ระบบเรียบร้อย');
          setTimeout(() => {
            if (data.status === 'เจ้าของร้าน') {
              location.href = 'pages/dashboard_superadmin/';
            } else if (data.status === 'พนักงาน') {
              location.href = 'pages/dashboard_admin/';
            } else {
              location.href = 'pages/default_dashboard/';
            }
          }, 800);
        }).fail(function(data) {
          window.toastr.remove();
          toastr.error('ไม่สามารถเข้าสู่ระบบได้');
        });
      });
    });
  </script>
</body>

</html>