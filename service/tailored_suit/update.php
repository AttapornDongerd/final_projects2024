<?php
header('Content-Type: application/json');
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['ord_id'])) {
    $response = [
        'status' => false,
        'message' => 'Missing sew_id in the request'
    ];
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit();
}

$tailor_status = $data['tailor_status'];
$tailor_date = $data['tailor_date'];
$emp_id = $data['emp_id'];
$ord_id = $data['ord_id'];

$checkQueueStmt = $conn->prepare("SELECT COUNT(*) FROM tailor_suit WHERE ord_id = :ord_id");
$checkQueueStmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
$checkQueueStmt->execute();
$countQueue = $checkQueueStmt->fetchColumn();

if ($countQueue == 0) {
    $response = [
        'status' => false,
        'message' => 'Invalid ord_id'
    ];
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit();
}

// Update queu_e table
$stmt = $conn->prepare("UPDATE tailor_suit SET tailor_status = :tailor_status, tailor_date = :tailor_date, emp_id = :emp_id WHERE ord_id = :ord_id");
$stmt->bindParam(":tailor_status", $tailor_status, PDO::PARAM_STR);
$stmt->bindParam(":tailor_date", $tailor_date, PDO::PARAM_STR);
$stmt->bindParam(":emp_id", $emp_id, PDO::PARAM_STR);
$stmt->bindParam(":ord_id", $sew_id, PDO::PARAM_STR);

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
    http_response_code(500); // Internal Server Error
    echo json_encode($response);
}
?>
