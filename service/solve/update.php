<?php
header('Content-Type: application/json');
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);



if (!isset($data['ord_id']) || !isset($data['solv_detail']) || !isset($data['queue_datefinish'])) {
    $response = [
        'status' => false,
        'message' => 'Missing required parameters'
    ];
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit();
}

$ord_id = $data['ord_id'];
// $ord_status_check = $data['ord_status_check'];
$queue_datefinish = $data['queue_datefinish'];

$stmtCheckOrder = $conn->prepare("SELECT * FROM order_sewing WHERE ord_id = :ord_id");
$stmtCheckOrder->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$stmtCheckOrder->execute();
if ($stmtCheckOrder->rowCount() == 0) {
    $response = [
        'status' => false,
        'message' => 'Invalid ord_id'
    ];
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit();
}

$statusInt = 3;
$stmtUpdateOrder = $conn->prepare("UPDATE order_sewing SET ord_status_check = :statusInt WHERE ord_id = :ord_id");
$stmtUpdateOrder->bindParam(":statusInt", $statusInt, PDO::PARAM_INT);
$stmtUpdateOrder->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$stmtUpdateOrder->execute();



$solv_detail = $data['solv_detail'];
$solv_date = date("Y-m-d H:i:s");
$stmtSolv = $conn->prepare("INSERT INTO solve (ord_id, solv_detail, solv_date) VALUES (:ord_id, :solv_detail, :solv_date) ON DUPLICATE KEY UPDATE solv_detail = VALUES(solv_detail), solv_date = VALUES(solv_date)");
$stmtSolv->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$stmtSolv->bindParam(":solv_detail", $solv_detail, PDO::PARAM_STR);
$stmtSolv->bindParam(":solv_date", $solv_date, PDO::PARAM_STR);
$stmtSolv->execute();

$stmtUpdateQueue = $conn->prepare("UPDATE queu_e SET queue_datefinish = :queue_datefinish WHERE ord_id = :ord_id");
$stmtUpdateQueue->bindParam(":queue_datefinish", $queue_datefinish, PDO::PARAM_STR);
$stmtUpdateQueue->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);


if ($stmtUpdateQueue->execute()) {
    $response = ['status' => true, 'message' => 'Update Success'];
    http_response_code(200);
    echo json_encode($response);
} else {
    $response = [
        'status' => false,
        'message' => 'Update Failed'
    ];
    http_response_code(500); // Internal Server Error
    echo json_encode($response);
}
