<?php 
require '../../DatabaseConnections.php';

$method = $_POST['method'];

function count_account_list($search_arr, $db) {
	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "SELECT count(id) AS total 
					FROM user_accounts 
					WHERE id_number LIKE '".$search_arr['employee_no']."%' 
					AND full_name LIKE '".$search_arr['full_name']."%' 
					AND role LIKE '".$search_arr['user_type']."%'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt->fetchALL() as $row){
				$total = $row['total'];
			}
		} else {
			$total = 0;
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
		$total = 0;
    }

	// Connection Close
	$conn = null;
    
	return $total;
}

if ($method == 'count_account_list') {
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];

	$search_arr = array(
		"employee_no" => $employee_no,
		"full_name" => $full_name,
		"user_type" => $user_type
	);

	echo count_account_list($search_arr, $db);
}

if ($method == 'account_list') {
	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 10;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		// MYSQL
		$query = "SELECT * FROM user_accounts 
					LIMIT ".$page_first_result.", ".$results_per_page;
		// MS SQL SERVER
		// $query = "SELECT * FROM user_accounts 
		// 			ORDER BY id ASC 
		// 			OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt->fetchALL() as $row) {
				$c++;
				echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['id_number'].'</td>';
				echo '<td>'.$row['username'].'</td>';
				echo '<td>'.$row['full_name'].'</td>';
				echo '<td>'.$row['section'].'</td>';
				echo '<td>'.strtoupper($row['role']).'</td>';
				echo '</tr>';
			}
		} else {
			echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
			echo '</tr>';
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;
}

if ($method == 'account_list_pagination') {
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];

	$search_arr = array(
		"employee_no" => $employee_no,
		"full_name" => $full_name,
		"user_type" => $user_type
	);

	$results_per_page = 10;

	$number_of_result = intval(count_account_list($search_arr, $db));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	for ($page = 1; $page <= $number_of_page; $page++) {
		echo '<option value="'.$page.'">'.$page.'</option>';
    }

}

if ($method == 'account_list_last_page') {
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];

	$search_arr = array(
		"employee_no" => $employee_no,
		"full_name" => $full_name,
		"user_type" => $user_type
	);

	$results_per_page = 10;

	$number_of_result = intval(count_account_list($search_arr, $db));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;

}

if ($method == 'search_account_list') {
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 10;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		// MYSQL
		$query = "SELECT * FROM user_accounts 
					WHERE id_number LIKE '$employee_no%' 
					AND full_name LIKE '$full_name%' AND role LIKE '$user_type%' 
					LIMIT ".$page_first_result.", ".$results_per_page;
		// MS SQL SERVER
		// $query = "SELECT * FROM user_accounts 
		// 			WHERE id_number LIKE '$employee_no%' AND full_name LIKE '$full_name%' 
		// 			AND role LIKE '$user_type%' 
		// 			ORDER BY id ASC 
		// 			OFFSET ".$page_first_result." ROWS FETCH NEXT ".$results_per_page." ROWS ONLY";

		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt->fetchALL() as $row) {
				$c++;
				echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['id_number'].'</td>';
				echo '<td>'.$row['username'].'</td>';
				echo '<td>'.$row['full_name'].'</td>';
				echo '<td>'.$row['section'].'</td>';
				echo '<td>'.strtoupper($row['role']).'</td>';
				echo '</tr>';
			}
		} else {
			echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
			echo '</tr>';
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;
}
