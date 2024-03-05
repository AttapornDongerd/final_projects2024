<?php
    header('Content-Type: application/json');
    require_once '../connect.php';
?>

<?php
    #process
    $sql = "SELECT order_sewing.*, employees.emp_id, employees.emp_name, design.detail FROM order_sewing 
    LEFT JOIN employees ON order_sewing.emp_id = employees.emp_id
    LEFT JOIN design ON order_sewing.design_id = design.design_id";
    
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