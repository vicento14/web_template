<?php 
require '../../DatabaseConnections.php';

if (!isset($_POST['method'])) {
	echo 'method not set';
}

$method = $_POST['method'];

if ($method == 'account_list') {
    $data = array();

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
			foreach($stmt->fetchALL() as $row) {
				$data[] = array(
					'id' => $row['id'],
					'id_number' => $row['id_number'],
					'username' => htmlspecialchars($row['username']),
					'full_name' => htmlspecialchars($row['full_name']),
					'section' => $row['section'],
					'role' => $row['role']
				);
			}
		} else {
			$data[] = array(
				'message' => 'No Results Found'
			);
		}
	} else {
		$data[] = array(
			'message' => $connectionArr['title'] . " " . $connectionArr['message']
		);
    }

    // Connection Close
    $conn = null;

    header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

if ($method == 'search_account_list') {
	$employee_no = $_POST['employee_no'];
	$full_name = $_POST['full_name'];
	$user_type = $_POST['user_type'];

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "SELECT * FROM user_accounts WHERE id_number LIKE '$employee_no%' 
            AND full_name LIKE '$full_name%' AND role LIKE '$user_type%'";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $row) {
				$data[] = array(
					'id' => $row['id'],
					'id_number' => $row['id_number'],
					'username' => htmlspecialchars($row['username']),
					'full_name' => htmlspecialchars($row['full_name']),
					'section' => $row['section'],
					'role' => $row['role']
				);
			}
		} else {
			$data[] = array(
				'message' => 'No Results Found'
			);
		}
	} else {
		$data[] = array(
			'message' => $connectionArr['title'] . " " . $connectionArr['message']
		);
    }

    // Connection Close
    $conn = null;

    header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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

		$query = "SELECT id FROM user_accounts WHERE username = ?";
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$params = array($username);
		$stmt->execute($params);
		if ($stmt->rowCount() > 0) {
			echo 'duplicate';
		} else {
			$query = "UPDATE user_accounts SET id_number = ?, username = ?, 
					full_name = ?, section = ?, role = ?";
			if (!empty($password)) {
				$query .= ", password = ?";
			}
			$query .= " WHERE id = ?";

			$params = array();
			
			if (!empty($password)) {
				$params = array($id_number, $username, $full_name, $section, $role, $password, $id);
			} else {
				$params = array($id_number, $username, $full_name, $section, $role, $id);
			}

			$stmt = $conn->prepare($query);
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

if ($method == 'delete_account') {
	$id = $_POST['id'];

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		$query = "DELETE FROM user_accounts WHERE id = ?";
		$stmt = $conn->prepare($query);
		$params = array($id);
		if ($stmt->execute($params)) {
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

if ($method == 'delete_account_selected') {
	$id_arr = [];
	$id_arr = $_POST['id_arr'];

	$count = count($id_arr);

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		foreach ($id_arr as $id) {
			$sql = "DELETE FROM user_accounts WHERE id = ?";
			$stmt = $conn -> prepare($sql);
			$params = array($id);
			$stmt -> execute($params);
			$count--;
		}
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;

	if ($count == 0) {
		echo 'success';
	}
}
