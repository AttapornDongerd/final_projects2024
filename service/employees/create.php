<?php
header('Content-Type: application/json');
require_once '../connect.php';

if (!isset($_POST['emp_id'], $_POST['emp_user'], $_POST['emp_name'], $_POST['emp_pass'], $_POST['emp_tel'], $_POST['status'])) {
    $response = [
        'status' => false,
        'message' => 'Missing required data'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$emp_id = $_POST['emp_id'];
$emp_user = $_POST['emp_user'];
$emp_name = $_POST['emp_name'];
$emp_pass = $_POST['emp_pass'];
$emp_tel = $_POST['emp_tel'];
$status = $_POST['status'];

$checkStmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE emp_user = :emp_user");
$checkStmt->bindParam(":emp_user", $emp_user, PDO::PARAM_STR);
$checkStmt->execute();
$count = $checkStmt->fetchColumn();

if ($count > 0) {
    $response = [
        'status' => false,
        'message' => 'Duplicate emp_user'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$checkStmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE emp_user = :emp_user");
$checkStmt->bindParam(":emp_user", $emp_user, PDO::PARAM_STR);
$checkStmt->execute();
$count = $checkStmt->fetchColumn();

if ($count > 0) {
    $response = [
        'status' => false,
        'message' => 'Duplicate emp_user'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO employees (emp_id, emp_name, emp_user, emp_pass, emp_tel, status, updated_at) 
            VALUES (:emp_id, :emp_name, :emp_user, :emp_pass, :emp_tel, :status, NOW())";

$stmt = $conn->prepare($sql);
$hashPassword = password_hash($emp_pass, PASSWORD_DEFAULT);

$stmt->bindParam(":emp_id", $emp_id, PDO::PARAM_STR);
$stmt->bindParam(":emp_name", $emp_name, PDO::PARAM_STR);
$stmt->bindParam(":emp_user", $emp_user, PDO::PARAM_STR);
$stmt->bindParam(":emp_pass", $hashPassword, PDO::PARAM_STR);
$stmt->bindParam(":emp_tel", $emp_tel, PDO::PARAM_STR);
$stmt->bindParam(":status", $status, PDO::PARAM_STR);

try {
    if ($stmt->execute()) {
        $response = [
            'status' => true,
            'message' => 'Create Success'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response = [
            'status' => false,
            'message' => 'Create failed'
        ];
        http_response_code(500);
        echo json_encode($response);
    }
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage());
    $response = [
        'status' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
}
?>