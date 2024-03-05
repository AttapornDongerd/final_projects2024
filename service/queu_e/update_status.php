<?php
    header('Content-Type: application/json');
    require_once '../connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $emp_id = $_SESSION['EMP_ID'];

    $ord_status_check = intval($data['ord_status_check']);
    $ord_id = $data['ord_id'];

    // อัปเดตข้อมูลสั่งตัดชุด
    $stmt = $conn->prepare("UPDATE order_sewing SET ord_status_check = :ord_status_check WHERE ord_id = :ord_id");

    $stmt->bindParam(":ord_status_check", $ord_status_check, PDO::PARAM_INT); 
    $stmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
    $stmt->execute();

    // เพิ่มข้อมูลลงในตาราง tailored_suit
    $tailore_date = date("Y-m-d H:i:s"); // วันที่และเวลาปัจจุบัน
    $stmtTailored = $conn->prepare("INSERT INTO tailored_suit (ord_id, tailore_date, emp_id) VALUES (:ord_id, :tailore_date, :emp_id) ON DUPLICATE KEY UPDATE tailore_date = VALUES(tailore_date), emp_id = VALUES(emp_id)");
    $stmtTailored->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
    $stmtTailored->bindParam(":tailore_date", $tailore_date, PDO::PARAM_STR);
    $stmtTailored->bindParam(":emp_id", $emp_id, PDO::PARAM_STR);
    $stmtTailored->execute();

    if ($stmt->execute() && $stmtTailored->execute()) {
        $response = [
            'status' => true,
            'message' => 'อัปเดตข้อมูลสำเร็จ'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response = [
            'status' => false,
            'message' => 'อัปเดตข้อมูลล้มเหลว'
        ];
        http_response_code(500);
        echo json_encode($response);
    }
?>