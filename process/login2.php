<?php
session_name("web_template");
session_start();

include 'conn.php';

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT full_name, section, role FROM user_accounts WHERE BINARY username = ? AND BINARY password = ?";
    $stmt = $conn->prepare($sql);
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
                header('location: ../page/admin/dashboard.php');
                exit;
            } elseif ($role == 'user') {
                header('location: ../page/user/pagination.php');
                exit;
            }
        }
    } else {
        $_SESSION['login_error'] = 1;
        header('location: ../index2.php');
        exit;
    }
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('location: ../index2.php');
}
?>