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
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
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

// Count
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

// Read / Load
if ($method == 'search_account_list') {
	$id = $_POST['id'];
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];
	$c = $_POST['c'];

    // Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

        // MYSQL
        $query = "SELECT * FROM user_accounts";
        // MS SQL SERVER
        // $query = "SELECT TOP 10 * FROM user_accounts";

        if (!empty($id)) {
            $query .= " WHERE id > '$id'";
            if (!empty($employee_no)) {
                $query .= " AND id_number LIKE '$employee_no%'";
            }
            if (!empty($full_name)) {
                $query .= " AND full_name LIKE '$full_name%'";
            }
            if (!empty($user_type)) {
                $query .= " AND role LIKE '$user_type%'";
            }
        } else if (!empty($employee_no) || 
                    !empty($full_name) || 
                    !empty($user_type)) {
            $query .= " WHERE 1=1";
            if (!empty($employee_no)) {
                $query .= " AND id_number LIKE '$employee_no%'";
            }
            if (!empty($full_name)) {
                $query .= " AND full_name LIKE '$full_name%'";
            }
            if (!empty($user_type)) {
                $query .= " AND role LIKE '$user_type%'";
            }
        }

        // MYSQL
        $query .= " ORDER BY id ASC LIMIT 10";
        // MS SQL SERVER
        // $query .= " ORDER BY id ASC";

		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt->fetchALL() as $row) {
				$c++;
				echo '<tr user-account-id="'.$row['id'].'">';
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