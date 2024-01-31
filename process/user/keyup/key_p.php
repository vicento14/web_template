<?php 
include '../../conn.php';

$method = $_POST['method'];

function count_account_list($search_arr, $conn) {
	$query = "SELECT count(id) AS total FROM user_accounts WHERE id_number LIKE '".$search_arr['employee_no']."%'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$total = $j['total'];
		}
	}else{
		$total = 0;
	}
	return $total;
}

if ($method == 'count_account_list') {
	$employee_no = $_POST['employee_no'];

	$search_arr = array(
		"employee_no" => $employee_no
	);

	echo count_account_list($search_arr, $conn);
}

if ($method == 'account_list') {
	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 10;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT * FROM user_accounts LIMIT ".$page_first_result.", ".$results_per_page;
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['id_number'].'</td>';
				echo '<td>'.$j['username'].'</td>';
				echo '<td>'.$j['full_name'].'</td>';
				echo '<td>'.$j['section'].'</td>';
				echo '<td>'.strtoupper($j['role']).'</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

if ($method == 'account_list_pagination') {
	$employee_no = $_POST['employee_no'];

	$search_arr = array(
		"employee_no" => $employee_no
	);

	$results_per_page = 10;

	$number_of_result = intval(count_account_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	for ($page = 1; $page <= $number_of_page; $page++) {
		echo '<option value="'.$page.'">'.$page.'</option>';
    }

}

if ($method == 'account_list_last_page') {
	$employee_no = $_POST['employee_no'];

	$search_arr = array(
		"employee_no" => $employee_no
	);

	$results_per_page = 10;

	$number_of_result = intval(count_account_list($search_arr, $conn));

	//determine the total number of pages available  
	$number_of_page = ceil($number_of_result / $results_per_page);

	echo $number_of_page;

}

if ($method == 'search_account_list') {
	$employee_no = $_POST['employee_no'];

	$current_page = intval($_POST['current_page']);
	$c = 0;

	$results_per_page = 10;

	//determine the sql LIMIT starting number for the results on the displaying page
	$page_first_result = ($current_page-1) * $results_per_page;

	$c = $page_first_result;

	$query = "SELECT * FROM user_accounts WHERE id_number LIKE '$employee_no%' LIMIT ".$page_first_result.", ".$results_per_page;
	$stmt = $conn->prepare($query);
	$stmt->execute();
	// GET QUERY COLUMN RESULT
	// if ($stmt->columnCount() > 0) {
	// 	echo '<tr>';
	// 	for ($i = 0; $i < $stmt->columnCount(); $i++) {
	// 		$col = $stmt->getColumnMeta($i); // 0 indexed so 0 would be first column
	// 		echo '<th>'.$col['name'].'</th>';
	// 	}
	// 	echo '</tr>';
	// }
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['id_number'].'</td>';
				echo '<td>'.$j['username'].'</td>';
				echo '<td>'.$j['full_name'].'</td>';
				echo '<td>'.$j['section'].'</td>';
				echo '<td>'.strtoupper($j['role']).'</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
		echo '</tr>';
	}
}

$conn = NULL;
?>