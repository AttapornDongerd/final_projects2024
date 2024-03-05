<?php
header('Content-Type: application/json');
require_once '../connect.php';

// ตรวจสอบว่าเป็น HTTP POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// ตรวจสอบว่ามีข้อมูลที่ถูกส่งมาหรือไม่
if (empty($_POST)) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'No data sent']);
    exit();
}

// ดึงข้อมูลที่ต้องการจาก $_POST
$design_id = $_POST['design_id'];
$detail = $_POST['detail'];
$starting_price = $_POST['starting_price'];
$period = $_POST['period'];

// ตรวจสอบว่ามีไฟล์รูปถูกส่งมาหรือไม่
if (isset($_FILES['design_image'])) {
    $design_image = basename($_FILES['design_image']['name']);
    $type = $_FILES['design_image']['type'];
    $size = $_FILES['design_image']['size'];
    $temp = $_FILES['design_image']['tmp_name'];

    $directory = "../../assets/images/";
    $path = $directory . $design_image;

    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];

    // ตรวจสอบว่าเป็นไฟล์รูปที่ถูกต้องหรือไม่
    if (in_array($type, $allowedTypes)) {
        // ตรวจสอบว่าไฟล์นี้มีอยู่หรือไม่
        if (!file_exists($path)) {
            // ตรวจสอบขนาดของไฟล์
            if ($size < 6000000) {
                // ย้ายไฟล์ไปยัง directory ที่กำหนด
                move_uploaded_file($temp, $path);
            } else {
                $errorMsg = "Your file is too large. Please upload a file with a size of 5MB or less.";
            }
        } else {
            $errorMsg = "File already exists. Check the upload folder.";
        }
    } else {
        $errorMsg = "Upload JPG, JPEG, PNG & GIF file formats only.";
    }
}

// ตรวจสอบว่าไม่มีข้อผิดพลาดจากการอัปโหลดไฟล์
if (!isset($errorMsg)) {
    // ตรวจสอบว่าได้รับรูปภาพมาหรือไม่ และกำหนดค่าตามไฟล์ที่อัปโหลด
    if (isset($design_image)) {
        $design_image_param = $design_image;
    } else {
        $design_image_param = null;
    }

    // if (isset($_FILES['design_image'])) {
    //     $stmt = $conn->prepare("UPDATE design SET design_image = :design_image, detail = :detail, starting_price = :starting_price, period = :period WHERE design_id = :design_id");

    //     $stmt->bindParam(":club_name", $club_name, PDO::PARAM_STR);
    //     $stmt->bindParam(":details", $details, PDO::PARAM_STR);
    //     $stmt->bindParam(":club_bureau", $club_bureau, PDO::PARAM_STR);
    //     $stmt->bindParam(':club_image', $club_image, PDO::PARAM_STR);
    //     $stmt->bindParam(":club_id", $club_id, PDO::PARAM_STR);
    // } else {

    $stmt = $conn->prepare("UPDATE design SET design_image = :design_image, detail = :detail, starting_price = :starting_price, period = :period WHERE design_id = :design_id");

    $stmt->bindParam(":design_image", $design_image_param, PDO::PARAM_STR);
    $stmt->bindParam(":detail", $detail, PDO::PARAM_STR);
    $stmt->bindParam(":starting_price", $starting_price, PDO::PARAM_STR);
    $stmt->bindParam(":period", $period, PDO::PARAM_STR);
    $stmt->bindParam(":design_id", $design_id, PDO::PARAM_STR);
    

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
} else {
    $response = [
        'status' => false,
        'message' => $errorMsg
    ];
    http_response_code(400);
    echo json_encode($response);
}
?>
