<?php
header('Content-Type: application/json');
require_once '../connect.php';
?>

<?php

$empid = $_SESSION['EMP_ID'];

#process
$sql = "SELECT order_sewing.*, employees.emp_name, design.detail, customer.cus_name, queu_e.queue_datefinish 
FROM order_sewing 
    LEFT JOIN employees ON order_sewing.emp_id = employees.emp_id
    LEFT JOIN design ON order_sewing.design_id = design.design_id
    LEFT JOIN customer ON order_sewing.cus_id = customer.cus_id
    LEFT JOIN queu_e ON order_sewing.ord_id = queu_e.ord_id
    WHERE order_sewing.emp_id = :empid AND ord_status_check != 4" ;

$stmt = $conn->prepare($sql);
$stmt->bindParam(':empid', $empid);
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