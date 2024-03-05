<?php
    header('Content-Type: application/json');
    require_once '../connect.php';
?>

<?php
    $data = json_decode(file_get_contents("php://input"), true);
    $cus_id = $data['cus_id'];

    $sql = "DELETE FROM customer WHERE cus_id = :cus_id";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":cus_id", $cus_id, PDO::PARAM_STR);
    try {
        $stmt->execute();

        $response = [
            'status' => true,
            'message' => 'Delete Success'
        ];
        http_response_code(204);
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = [
            'status' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
        http_response_code(500);
        echo json_encode($response);
        echo 'Error: ' . $e->getMessage();
    }
?>