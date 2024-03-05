<?php
    header('Content-Type: application/json');
    require_once '../connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['cus_id'])) {
        $response = [
            'status' => false,
            'message' => 'Missing branch_id in the request'
        ];
        http_response_code(400);
        echo json_encode($response);
        exit();
    }

    $cus_name = $data['cus_name'];
    $cus_tel = $data['cus_tel'];
    $cus_id = $data['cus_id'];

    $checkCustomerStmt = $conn->prepare("SELECT COUNT(*) FROM customer WHERE cus_id = :cus_id");
    $checkCustomerStmt->bindParam(":cus_id", $cus_id, PDO::PARAM_STR);
    $checkCustomerStmt->execute();
    $countCustomer = $checkCustomerStmt->fetchColumn();

    if ($countCustomer == 0) {
        $response = [
            'status' => false,
            'message' => 'Invalid faculty_id or cus_id'
        ];
        http_response_code(400);
        echo json_encode($response);
        exit();
    }

    $stmt = $conn->prepare("UPDATE customer 
                            SET cus_name = :cus_name, cus_tel = :cus_tel WHERE cus_id = :cus_id");

    $stmt->bindParam(":cus_name", $cus_name, PDO::PARAM_STR);
    $stmt->bindParam(":cus_tel", $cus_tel, PDO::PARAM_STR);
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
        http_response_code(500); // Internal Server Error
        echo json_encode($response);
    }
?>