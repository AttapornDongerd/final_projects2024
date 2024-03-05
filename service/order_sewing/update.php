<?php
header('Content-Type: application/json');
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$ord_id  = $data['ord_id'];
$ord_idBP  = $data['ord_id'];
$detail_measure = $data['detail_measure'];
$ord_size = $data['ord_size'];
$order_price = $data['order_price'];
$detailmore = $data['detailmore'];
$price_detailmore = $data['price_detailmore'];
$total_price = $data['total_price'];
$design_id = $data['design_id'];

$ord_date = $data['ord_date'];
$period_days = $data['period_date']; // จำนวนวันที่ต้องการเพิ่มเข้าไป

// // แปลงวันที่เป็น timestamp
$ord_timestamp = strtotime($ord_date);

// // เพิ่มจำนวนวันลงไปใน timestamp
$ord_timestamp += ($period_days * 86400); // 86400 คือจำนวนวินาทีในหนึ่งวัน

// // แปลง timestamp กลับเป็นรูปแบบวันที่
$new_date = date("Y-m-d", $ord_timestamp);


$checkOrder_sewingStmt = $conn->prepare("SELECT COUNT(*) FROM order_sewing WHERE ord_id = :ord_id");
$checkOrder_sewingStmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$checkOrder_sewingStmt->execute();
$countOrder_sewing = $checkOrder_sewingStmt->fetchColumn();

if ($countOrder_sewing == 0) {
    $response = [
        'status' => false,
        'message' => 'รหัสการสั่งตัดชุดหรือรหัสไม่ถูกต้อง'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}


$stmt = $conn->prepare("UPDATE order_sewing SET detail_measure = :detail_measure, ord_size = :ord_size,
                            order_price = :order_price, detailmore = :detailmore, price_detailmore = :price_detailmore, total_price = :total_price, ord_date = :ord_date, design_id = :design_id WHERE ord_id = :ord_id");

$stmt->bindParam(":detail_measure", $detail_measure, PDO::PARAM_STR);
$stmt->bindParam(":ord_size", $ord_size, PDO::PARAM_STR);
$stmt->bindParam(":order_price", $order_price, PDO::PARAM_STR);
$stmt->bindParam(":detailmore", $detailmore, PDO::PARAM_STR);
$stmt->bindParam(":price_detailmore", $price_detailmore, PDO::PARAM_STR);
$stmt->bindParam(":total_price", $total_price, PDO::PARAM_STR);
$stmt->bindParam(":ord_date", $ord_date, PDO::PARAM_STR);
$stmt->bindParam(":design_id", $design_id, PDO::PARAM_STR);
$stmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$stmt->execute();

if ($stmt) {

    $stmt2 = $conn->prepare("UPDATE queu_e SET ord_date = :ord_date, queue_datefinish = :new_date WHERE ord_id = :ord_idBP");

    $stmt2->bindParam(":ord_date", $ord_date, PDO::PARAM_STR);
    $stmt2->bindParam(":new_date", $new_date, PDO::PARAM_STR);
    $stmt2->bindParam(":ord_idBP", $ord_idBP, PDO::PARAM_STR);

    if ($stmt2->execute()) {
        $response = ['status' => true, 'message' => 'Update Success'];
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response = [
            'status' => false,
            'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล'
        ];
        http_response_code(500); // ใช้รหัสสถานะ HTTP 500 สำหรับข้อผิดพลาดที่เกิดขึ้นในฝั่งเซิร์ฟเวอร์
        echo json_encode($response);
    }
} else {
    $response = [
        'status' => false,
        'message' => 'อัปเดตข้อมูลล้มเหลว'
    ];
    http_response_code(500);
    echo json_encode($response);
}
