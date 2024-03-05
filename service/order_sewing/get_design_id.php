<?php
require_once '../connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    // Decode the received design ID
    $odr_Id = urldecode($_POST['order_id']);

    try {
        // Assuming $conn is the PDO connection object from connect.php
        // Query to fetch design details
        $query = "SELECT * FROM order_sewing WHERE ord_id = :odr_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':odr_id', $odr_Id);
        $stmt->execute();

        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $response = [
                'status' => true,
                'data' => $result,
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'ไม่พบข้อมูลแบบชุดสำหรับ ' . $odr_Id,
                'received_detail' => $odr_Id,
            ];
        }
    } catch (PDOException $e) {
        $response = [
            'status' => false,
            'message' => 'เกิดข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล: ' . $e->getMessage(),
        ];
    }

    // Return the JSON response with JSON_UNESCAPED_UNICODE
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}
?>
