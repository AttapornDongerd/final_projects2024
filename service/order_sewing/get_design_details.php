<?php
require_once '../connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['design_id'])) {
    // Decode the received design ID
    $designId = urldecode($_POST['design_id']);

    try {
        // Assuming $conn is the PDO connection object from connect.php
        // Query to fetch design details
        $query = "SELECT design_id, design_image, detail, starting_price, period FROM design WHERE design_id = :design_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':design_id', $designId);
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
                'message' => 'ไม่พบข้อมูลแบบชุดสำหรับ ' . $designId,
                'received_detail' => $designId,
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
