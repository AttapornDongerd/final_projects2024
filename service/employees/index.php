<?php
header('Content-Type: application/json');
require_once '../connect.php';
?>

<?php
#process
    $sql = "SELECT * FROM employees";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $response = [
        'status' => true,
        'response' => [],
        'message' => 'Get Data Manager Success'
    ];
    //ดึงข้อมูลจาก $response ไปแสดงผล
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $response['response'][] = $row;
    }

    http_response_code(200);
    echo json_encode($response);
?>