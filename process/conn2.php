<?php
date_default_timezone_set('Asia/Manila');
//$servername = '172.25.112.131, 1433\SQLEXPRESS'; $username = 'SA'; $password = 'SystemGroup2018';
$servername = 'DESKTOP-TRJMO4S\SQLEXPRESS'; $username = 'web_template'; $password = 'SystemGroup2018';

try {
    $conn = new PDO ("sqlsrv:Server=$servername;Database=web_template",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION'.$e->getMessage();
}
?>