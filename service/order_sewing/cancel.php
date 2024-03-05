<?php
    header('Content-Type: application/json');
    require_once '../connect.php';
    $data = json_decode(file_get_contents("php://input"), true);


    if(isset($data['ord_id'])) {
        $ord_id = $data['ord_id'];
        try {
            $stmt = $conn->prepare("UPDATE order_sewing SET ord_status_check = 4 WHERE ord_id = :ord_id");
            $stmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(array("status" => "success", "message" => "บันทึกข้อมูลเรียบร้อย"));
        } catch(PDOException $e) {
            echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาด: ".$e->getMessage()));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "ไม่สามารถทำรายการได้ เนื่องจากข้อมูลไม่ครบถ้วน"));
    }
?>