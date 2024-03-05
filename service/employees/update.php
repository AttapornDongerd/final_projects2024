<?php
header('Content-Type: application/json');
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['emp_user'])) {
    $response = [
        'status' => false,
        'message' => 'Missing emp_user in the request'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$emp_user = $data['emp_user'];
$emp_name = isset($data['emp_name']) ? $data['emp_name'] : null;
$emp_tel = isset($data['emp_tel']) ? $data['emp_tel'] : null;
$status = isset($data['status']) ? $data['status'] : null;
$emp_id = isset($data['emp_id']) ? $data['emp_id'] : null;

$checkEmployeesStmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE emp_user = :emp_user");
$checkEmployeesStmt->bindParam(":emp_user", $emp_user, PDO::PARAM_STR);
$checkEmployeesStmt->execute();
$countEmployees = $checkEmployeesStmt->fetchColumn();

if ($countEmployees == 0) {
    $response = [
        'status' => false,
        'message' => 'Invalid emp_user'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$stmt = $conn->prepare("UPDATE employees 
    SET emp_name = :emp_name, emp_tel = :emp_tel,
    status = :status, emp_id = :emp_id 
    WHERE emp_user = :emp_user");

$stmt->bindParam(":emp_name", $emp_name, PDO::PARAM_STR);
$stmt->bindParam(":emp_tel", $emp_tel, PDO::PARAM_STR);
$stmt->bindParam(":status", $status, PDO::PARAM_STR);
$stmt->bindParam(":emp_id", $emp_id, PDO::PARAM_STR);
$stmt->bindParam(":emp_user", $emp_user, PDO::PARAM_STR);



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
