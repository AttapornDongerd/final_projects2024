<?php
    require_once('../../TCPDF-main/tcpdf.php');
    require_once('../../service/pdf/order.php');

    if (!isset($_GET['ord_id'])) {
        echo "ord_id is missing in the URL.";
        exit();
    }
    
    $ord_id = $_GET['ord_id'];

    generateOrderPDF($ord_id);
?>