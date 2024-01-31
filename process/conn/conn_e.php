<?php
date_default_timezone_set('Asia/Manila');
$servername = '172.25.114.162\\SQLEXPRESS'; $username = 'SA'; $password = 'SystemGroup2018';

try {
    $conn = new PDO ("sqlsrv:Server=$servername;Database=new_ekanban",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION'.$e->getMessage();
}
?>