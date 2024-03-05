<?php
    header('Content-Type: application/json');
    require_once '../connect.php';
?>

<?php
    $sql = "SELECT * FROM customer";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $response = [
        'status' => true,
        'message' => 'Get Data Manager Success'
    ];

    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $response['response'][] = $row;
    }

    http_response_code(200);
    echo json_encode($response);
?>