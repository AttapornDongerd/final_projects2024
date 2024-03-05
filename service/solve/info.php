<?php
    header('Content-Type: application/json');
    require_once '../connect.php';
?>

<?php
    #process
    $sql = "SELECT
                ts.*,
                os.ord_id,
                os.detail_measure,
                os.ord_size,
                e.emp_name,
                d.design_id,
                d.detail AS design_detail,
                c.cus_name
            FROM
                tailored_suit ts
            LEFT JOIN
                order_sewing os ON ts.order_sewing_id = os.ord_id
            LEFT JOIN
                employees e ON ts.employee_id = e.emp_id
            LEFT JOIN
                design d ON ts.design_id = d.design_id
            LEFT JOIN
                customer c ON ts.customer_id = c.cus_id
            WHERE ord_id = ord_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $response = [
        'status' => true,
        'message' => 'Get Data Manager Success'
    ];

    // ดึงข้อมูลจาก $response ไปแสดงผล
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $response['response'][] = $row;
    }

    http_response_code(200);
    echo json_encode($response);
?>
