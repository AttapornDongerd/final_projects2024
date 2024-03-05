<?php

echo '<pre>';
    print_r($_POST);
echo '</pre>';

?>


        $('#formData').on('submit', function(e) {
            var formData = {
                ord_id: $('#ord_id').val(),
                solv_detail: $('#solv_detail').val(),
                queue_datefinish: $('#queue_datefinish').val(),
                cus_name: $('#cus_name').val(),
                detail_measure: $('#detail_measure').val()
            };

            $.ajax({
                type: 'POST',
                url: '../../service/solve/update.php',
                data: JSON.stringify(formData), // Convert formData to JSON
                contentType: 'application/json', // Set content type to JSON
                dataType: 'json',
                success: function(resp) {
                    if (resp.status) {
                        Swal.fire({
                            text: 'แก้ไขข้อมูลเรียบร้อย',
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