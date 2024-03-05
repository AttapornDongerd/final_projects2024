<?php
header('Content-Type: application/json');
require_once '../connect.php';

// ดึงเลขลำดับล่าสุดของ cus_id จากตาราง customer
// $maxCustomerStmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING(cus_id, 2) AS SIGNED)) FROM customer");
// $maxCustomerStmt->execute();
// $maxCustomerNumber = $maxCustomerStmt->fetchColumn();
// $nextCustomerNumber = $maxCustomerNumber + 1;
// $cus_id = 'C00' . sprintf('%01d', $nextCustomerNumber);

$cus_id = $_POST['cus_id'];
$cus_name = $_POST['cus_name'];
$cus_tel = $_POST['cus_tel'];

$checkStmt = $conn->prepare("SELECT COUNT(*) FROM customer WHERE cus_id = :cus_id");
$checkStmt->bindParam(":cus_id", $cus_id, PDO::PARAM_STR);
$checkStmt->execute();
$count = $checkStmt->fetchColumn();

if ($count > 0) {
    $response = [
        'status' => false,
        'message' => 'รหัสลูกค้าซ้ำ'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO customer (cus_id, cus_name, cus_tel) 
        VALUES (:cus_id, :cus_name, :cus_tel)";

$stmt = $conn->prepare($sql);

$stmt->bindParam(":cus_id", $cus_id, PDO::PARAM_STR);
$stmt->bindParam(":cus_name", $cus_name, PDO::PARAM_STR);
$stmt->bindParam(":cus_tel", $cus_tel, PDO::PARAM_STR);

try {
    if ($stmt->execute()) {
        $response = [
            'status' => true,
            'message' => 'เพิ่มข้อมูลสำเร็จ'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response = [
            'status' => false,
            'message' => 'เพิ่มข้อมูลล้มเหลว'
        ];
        http_response_code(500);
        echo json_encode($response);
    }
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage());
    $response = [
        'status' => false,
        'message' => 'ข้อผิดพลาด: ' . $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
}
?>