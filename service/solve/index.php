<?php
header('Content-Type: application/json');
require_once '../connect.php';

#process
$empid = $_SESSION['EMP_ID'];

$sql = "SELECT tailored_suit.*, order_sewing.ord_status_check, queu_e.queue_datefinish FROM tailored_suit
        LEFT JOIN order_sewing ON tailored_suit.ord_id = order_sewing.ord_id
        LEFT JOIN queu_e ON tailored_suit.ord_id = queu_e.ord_id
        WHERE tailored_suit.emp_id = :empid AND order_sewing.ord_status_check=2";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':empid', $empid);
$stmt->execute();

$response = [
    'status' => true,
    'message' => 'Get Data Manager Success'
];

// Fetch data from the database and add it to the response array
while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    $response['response'][] = $row;
}

http_response_code(200);
echo json_encode($response);
