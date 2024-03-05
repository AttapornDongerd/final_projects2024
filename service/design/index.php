<?php
// Backend PHP API (queu_e/index.php)
header('Content-Type: application/json');
require_once '../connect.php';

$sql = "SELECT * FROM design";

$stmt = $conn->prepare($sql);
$stmt->execute();

$response = [
    'status' => true,
    'message' => 'Get Data Manager Success',
    'response' => []
];

while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    $response['response'][] = $row;
}

http_response_code(200);
echo json_encode($response);
