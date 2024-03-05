<?php
// เชื่อมต่อฐานข้อมูล
require_once '../connect.php';

// ตรวจสอบว่ามีการส่งค่า or_id หรือไม่
if (isset($_POST['ord_id'])) {
    $or_id = $_POST['ord_id'];

    try {
        // อ่านค่าปัจจุบันของสถานะ
        $getStatusQuery = "SELECT status FROM tailored_suit WHERE ord_id = ?";
        $stmt = $conn->prepare($getStatusQuery);
        $stmt->execute([$ord_id]);
        $current_status = $stmt->fetchColumn();

        // กำหนดสถานะใหม่ตามลำดับ
        $new_status = ($current_status < 2) ? $current_status + 1 : $current_status;

        // อัปเดตสถานะในตาราง orders โดยใช้ค่า or_id ที่ได้รับมา
        $updateStatusQuery = "UPDATE tailored_suit SET status = ? WHERE ord_id = ?";
        $stmt = $conn->prepare($updateStatusQuery);
        $stmt->execute([$new_status, $ord_id]);

        // ส่งข้อความกลับให้ผู้ใช้ทราบว่าการอัปเดตสถานะสำเร็จ
        $response = array('status' => true, 'message' => 'Order status updated successfully');
        echo json_encode($response);
    } catch (PDOException $e) {
        // กรณีเกิดข้อผิดพลาดในการอัปเดตสถานะ
        $response = array('status' => false, 'message' => 'Error updating order status: ' . $e->getMessage());
        echo json_encode($response);
    }
} else {
    // ถ้าไม่มีค่า or_id ที่ส่งมา
    $response = array('status' => false, 'message' => 'Invalid parameters');
    echo json_encode($response);
}
