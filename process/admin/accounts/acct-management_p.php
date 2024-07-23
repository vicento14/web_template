<?php 
require '../../DatabaseConnections.php';

$method = $_POST['method'];

if ($method == 'account_list') {
	$c = 0;

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "SELECT * FROM user_accounts";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $row){
				$c++;
				echo '<tr style="cursor:pointer;" class="modal-trigger" 
						data-toggle="modal" data-target="#update_account" 
						onclick="get_accounts_details(&quot;'.$row['id'].'~!~'.
						$row['id_number'].'~!~'.$row['username'].'~!~'.$row['full_name'].'~!~'.
						$row['section'].'~!~'.$row['role'].'&quot;)">';
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

if ($method == 'search_account_list') {
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];
	$c = 0;

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "SELECT * FROM user_accounts 
					WHERE id_number LIKE '$employee_no%' 
					AND full_name LIKE '$full_name%' 
					AND role LIKE '$user_type%'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $row) {
				$c++;
				echo '<tr style="cursor:pointer;" class="modal-trigger" 
					data-toggle="modal" data-target="#update_account" 
					onclick="get_accounts_details(&quot;'.$row['id'].'~!~'.
					$row['id_number'].'~!~'.$row['username'].'~!~'.$row['full_name'].'~!~'.
					$row['section'].'~!~'.$row['role'].'&quot;)">';
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

if ($method == 'register_account') {
	$employee_no = trim($_POST['employee_no']);
	$full_name = trim($_POST['full_name']);
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$section = trim($_POST['section']);
	$user_type = trim($_POST['user_type']);

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$check = "SELECT id FROM user_accounts WHERE username = ?";
		$stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$params = array($username);
		$stmt->execute($params);
		if ($stmt->rowCount() > 0) {
			echo 'Already Exist';
		} else {
			$query = "INSERT INTO user_accounts 
					(id_number, full_name, username, password, section, role) 
					VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($query);
			$params = array($employee_no, $full_name, $username, $password, $section, $user_type);
			if ($stmt->execute($params)) {
				echo 'success';
			} else {
				echo 'error';
			}
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;
}

if ($method == 'update_account') {
	$id = $_POST['id'];
	$id_number = trim($_POST['id_number']);
	$username = trim($_POST['username']);
	$full_name = trim($_POST['full_name']);
	$password = trim($_POST['password']);
	$section = trim($_POST['section']);
	$role = trim($_POST['role']);

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "SELECT id FROM user_accounts 
				WHERE username = '$username' AND id_number = '$id_number' 
				AND full_name = '$full_name' AND section = '$section'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			echo 'duplicate';
		} else {
			$query = "UPDATE user_accounts 
						SET id_number = '$id_number', username = '$username', 
						full_name = '$full_name', section = '$section', role = '$role'";
			if (!empty($password)) {
				$query .= ", password = '$password'";
			}
			$query .= " WHERE id = '$id'";

			$stmt = $conn->prepare($query);
			if ($stmt->execute()) {
				echo 'success';
			} else {
				echo 'error';
			}
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;
}

if ($method == 'delete_account') {
	$id = $_POST['id'];

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "DELETE FROM user_accounts WHERE id = '$id'";
		$stmt = $conn->prepare($query);
		if ($stmt->execute()) {
			echo 'success';
		} else {
			echo 'error';
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;
}
