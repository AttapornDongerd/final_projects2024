<?php

header('Content-Type: application/json');
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_user = :emp_user");
    $stmt->execute(array(":emp_user" => $_POST['emp_user']));
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if (!empty($row) && password_verify($_POST['emp_pass'], $row->emp_pass)) {
        $_SESSION['EMP_ID'] = $row->emp_id;
        $_SESSION['EMP_NAME'] = $row->emp_name;
        $_SESSION['EMP_user'] = $row->emp_user;
        $_SESSION['EMP_STATUS'] = $row->status;
        $_SESSION['EMP_TEL'] = $row->emp_tel;
        $_SESSION['EMP_LOGIN'] = $row->updated_at;

        $updateStmt = $conn->prepare("UPDATE employees SET updated_at = :updated_at WHERE emp_user = :emp_user");
        $updateStmt->execute(array(":updated_at" => date("Y-m-d H:i:s"), ":emp_user" => $row->emp_user));

        if ($updateStmt->rowCount() > 0) {
            http_response_code(200);

            // เพิ่มเงื่อนไขในการตรวจสอบสิทธิ์
            if ($_SESSION['EMP_STATUS'] == 'เจ้าของร้าน' && $_SESSION['EMP_ID'] == $row->emp_id) {
                echo json_encode(array('status' => true, 'message' => 'Login Success!', 'status' => $row->status));
            } elseif ($_SESSION['EMP_STATUS'] == 'พนักงาน' && $_SESSION['EMP_ID'] == $row->emp_id) {
                echo json_encode(array('status' => true, 'message' => 'Login Success!', 'status' => $row->status));
            } else {
                echo json_encode(array('status' => false, 'message' => 'Unauthorized Access!'));
            }
        } else {
            http_response_code(500);
            echo json_encode(array('status' => false, 'message' => 'Failed to update login timestamp!'));
        }
    } else {
        http_response_code(401);
        echo json_encode(array('status' => false, 'massage' => 'Unauthorized!'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'massage' => 'Method Not Allowed'));
}
?>
