<?php
session_name("web_template");
session_start();

require 'DatabaseConnections.php';

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

        // MySQL
        $sql = "SELECT full_name, section, role FROM user_accounts 
                WHERE BINARY username = ? AND BINARY password = ?";
        // MS SQL Server
        // $sql = "SELECT full_name, section, role FROM user_accounts 
        //         WHERE username = ? COLLATE SQL_Latin1_General_CP1_CS_AS 
        //         AND password = ? COLLATE SQL_Latin1_General_CP1_CS_AS";
        $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $params = array($username, $password);
        $stmt->execute($params);
        if ($stmt->rowCount() > 0) {
            foreach($stmt->fetchALL() as $x){
                $name = $x['full_name'];
                $section = $x['section'];
                $role = $x['role'];
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $name;
                $_SESSION['section'] = $section;
                $_SESSION['role'] = $role;
                if ($role == 'admin') {
                    header('location: page/admin/dashboard.php');
                } elseif ($role == 'user') {
                    header('location: page/user/pagination.php');
                }
            }
        } else {
            echo '<script>
                alert("Sign In Failed. Maybe an incorrect credential or account not found")
                </script>';
        }
    } else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('location: /web_template/');
}
