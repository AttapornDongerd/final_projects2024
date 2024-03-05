<?php
header('Content-Type: application/json');
require_once '../connect.php';


// $maxDesignStmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING(design_id, 7) AS SIGNED)) FROM design");
// $maxDesignStmt->execute();
// $maxDesignNumber = $maxDesignStmt->fetchColumn();
// $nextDesignNumber = $maxDesignNumber + 1;
// $design_id = 'D-01-' . sprintf('%05d', $nextDesignNumber);


$checkStmt = $conn->prepare("SELECT COUNT(*) FROM design WHERE design_id = :design_id");
$checkStmt->bindParam(":design_id", $design_id, PDO::PARAM_STR);
$checkStmt->execute();
$count = $checkStmt->fetchColumn();

if ($count > 0) {
    $response = [
        'status' => false,
        'message' => 'รหัส design_id ซ้ำ'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$design_id = $_POST['design_id'];
$detail = $_POST['detail'];
$starting_price = $_POST['starting_price'];
$period = $_POST['period'];


$design_image = basename($_FILES['txt_file']['name']);
$type = $_FILES['txt_file']['type'];
$size = $_FILES['txt_file']['size'];
$temp = $_FILES['txt_file']['tmp_name'];

$uploadPath = '../../assets/images/' . $design_image;

$allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
if (in_array($type, $allowedTypes)) {
    if (!file_exists($uploadPath)) {
        if ($size < 5000000) {
            move_uploaded_file($temp, $uploadPath);
        } else {
            $errorMsg = "ไฟล์ของคุณมีขนาดใหญ่เกินไป; กรุณาอัพโหลดไฟล์ที่มีขนาดน้อยกว่า 5MB";
        }
    } else {
        $errorMsg = "ไฟล์นี้มีอยู่แล้ว... กรุณาตรวจสอบโฟลเดอร์ที่อัพโหลด";
    }
} else {
    $errorMsg = "รองรับเฉพาะไฟล์รูปภาพในรูปแบบ JPG, JPEG, PNG และ GIF เท่านั้น";
}

if (!isset($errorMsg)) {
    // เพิ่มข้อมูลลงในตาราง design
    $sql = "INSERT INTO design (design_id, design_image, detail, starting_price, period) 
                VALUES (:design_id, :design_image, :detail, :starting_price, :period)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":design_id", $design_id, PDO::PARAM_STR);
    $stmt->bindParam(":design_image", $design_image, PDO::PARAM_STR);
    $stmt->bindParam(":detail", $detail, PDO::PARAM_STR);
    $stmt->bindParam(":starting_price", $starting_price, PDO::PARAM_STR);
    $stmt->bindParam(":period", $period, PDO::PARAM_STR);

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
} else {
    $response = [
        'status' => false,
        'message' => $errorMsg
    ];
    http_response_code(500);
    echo json_encode($response);
}
