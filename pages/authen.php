<?php 
    require_once '../../service/connect.php' ; 
    if( !isset($_SESSION['EMP_ID'] ) ){
        header('Location: ../../login.php');  
    }
?>
