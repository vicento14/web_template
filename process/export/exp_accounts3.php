<?php
session_name("web_template");
session_start();

if (!isset($_SESSION['username'])) {
    header('location:../../');
} else if ($_SESSION['role'] == 'user') {
    header('location: ../../page/user/pagination.php');
}

require '../DatabaseConnections.php';

switch (true) {
    case !isset($_GET['employee_no']):
    case !isset($_GET['full_name']):
        echo 'Query Parameters Not Set';
        break;
}

$employee_no = $_GET['employee_no'];
$full_name = $_GET['full_name'];
$c = 0;

$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "Export Accounts 3 - " . $datenow . ".csv";

// Create a file pointer 
$f = fopen('php://memory', 'w');

// UTF-8 BOM for special character compatibility
fputs($f, "\xEF\xBB\xBF");

// Set column headers 
$fields = array('#', 'ID Number', 'Full Name', 'Username', 'Password', 'Section', 'Role');
fputcsv($f, $fields, $delimiter);

// Connection Object
$conn = null;

// Connection Open
$connectionArr = $db->connect();

if ($connectionArr['connected'] == 1) {
	$conn = $connectionArr['connection'];

    $sql = "SELECT id_number, full_name, username, password, section, role 
        FROM user_accounts WHERE id_number LIKE '$employee_no%' 
        AND full_name LIKE '$full_name%'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        // Output each row of the data, format line as csv and write to file pointer 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            $lineData = array(
                $c,
                $row['id_number'],
                $row['full_name'],
                $row['username'],
                $row['password'],
                $row['section'],
                $row['role']
            );
            fputcsv($f, $lineData, $delimiter);
        }
    }
} else {
	echo $connectionArr['title'] . " " . $connectionArr['message'];
}

// Connection Close
$conn = null;

// Move back to beginning of file 
fseek($f, 0);

// Set headers to download file rather than displayed 
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

//output all remaining data on a file pointer 
fpassthru($f);
