<?php
header('Content-Type: application/json');
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['ord_id'])) {
    $response = [
        'status' => false,
        'message' => 'Missing ord_id in the request'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$ord_date = $data['ord_date'];
$queue_datefinish = $data['queue_datefinish'];
$cus_id = $data['cus_id'];
$ord_id = $data['ord_id'];


$checkQueueStmt = $conn->prepare("SELECT COUNT(*) FROM queu_e WHERE ord_id = :ord_id");
$checkQueueStmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$checkQueueStmt->execute();
$countQueue = $checkQueueStmt->fetchColumn();

if ($countQueue == 0) {
    $response = [
        'status' => false,
        'message' => 'Invalid ord_id'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}
$stmt = $conn->prepare("UPDATE queu_e SET ord_date = :ord_date, queue_datefinish = :queue_datefinish, cus_id = :cus_id WHERE ord_id = :ord_id");
$stmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$stmt->bindParam(":ord_date", $ord_date, PDO::PARAM_STR);
$stmt->bindParam(":queue_datefinish", $queue_datefinish, PDO::PARAM_STR);
$stmt->bindParam(":cus_id", $cus_id, PDO::PARAM_STR);


if ($stmt->execute()) {
    $response = [
        'status' => true,
        'message' => 'Update Success'
    ];
    http_response_code(200);
    echo json_encode($response);
} else {
    $response = [
        'status' => false,
        'message' => 'Update Failed'
    ];
    http_response_code(500);
    echo json_encode($response);
}
