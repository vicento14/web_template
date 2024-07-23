<?php 
require '../../DatabaseConnections.php';

$method = $_POST['method'];

if ($method == 'load_t_t1') {
	$c = 0;

	echo '<thead style="text-align: center;">
            <tr>
              <th> # </th>
              <th> C1 </th>
              <th> C2 </th>
              <th> C3 </th>
              <th> C4 </th>
              <th> Date Updated </th>
            </tr>
          </thead>
          <tbody id="t_t1_data" style="text-align: center;">';

	$query = "SELECT * FROM t_t1";

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		// 1st Approach using SQL Server DB when using Select Query
		$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt->fetchALL() as $row) {
				$c++;
				echo '<tr style="cursor:pointer;" class="modal-trigger" 
						onclick="load_t_t2(&quot;'.$row['id'].'~!~'.$row['c1'].'&quot;)">';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['c1'].'</td>';
				echo '<td>'.$row['c2'].'</td>';
				echo '<td>'.$row['c3'].'</td>';
				echo '<td>'.$row['c4'].'</td>';
				echo '<td>'.$row['date_updated'].'</td>';
				echo '</tr>';
			}
		} else {
			echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
			echo '</tr>';
		}

		// 2nd Approach using SQL Server DB when using Select Query
		/*$stmt = $conn->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$c++;
				echo '<tr style="cursor:pointer;" class="modal-trigger" 
						onclick="load_t_t2(&quot;'.$row['id'].'~!~'.$row['c1'].'&quot;)">';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['c1'].'</td>';
				echo '<td>'.$row['c2'].'</td>';
				echo '<td>'.$row['c3'].'</td>';
				echo '<td>'.$row['c4'].'</td>';
				echo '<td>'.$row['date_updated'].'</td>';
				echo '</tr>';
			}
		}else{
			echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
			echo '</tr>';
		}*/
	} else {
        echo $connectionArr['title'] . " " . $connectionArr['message'];
    }

    // Connection Close
    $conn = null;

	echo '</tbody>';
}

if ($method == 'load_t_t2') {
	$c1 = $_POST['c1'];

	$c = 0;
	
	echo '<thead style="text-align: center;">
            <tr>
              <th> # </th>
              <th> C1 </th>
              <th> D1 </th>
              <th> D2 </th>
              <th> D3 </th>
              <th> Date Updated </th>
            </tr>
          </thead>
          <tbody id="t_t2_data" style="text-align: center;">';

	$query = "SELECT * FROM t_t2 WHERE c1 = '$c1'";

	// Connection Object
    $conn = null;

    // Connection Open
    $connectionArr = $db->connect();

    if ($connectionArr['connected'] == 1) {
        $conn = $connectionArr['connection'];

		// 1st Approach using SQL Server DB when using Select Query
		/*$stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach($stmt->fetchALL() as $row){
				$c++;
				echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['c1'].'</td>';
				echo '<td>'.$row['d1'].'</td>';
				echo '<td>'.$row['d2'].'</td>';
				echo '<td>'.$row['d3'].'</td>';
				echo '<td>'.$row['date_updated'].'</td>';
				echo '</tr>';
			}
		}else{
			echo '<tr>';
			echo '<td colspan="6" style="text-align:center; color:red;">No Result !!!</td>';
			echo '</tr>';
		}*/

		// 2nd Approach using SQL Server DB when using Select Query
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$c++;
				echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$row['c1'].'</td>';
				echo '<td>'.$row['d1'].'</td>';
				echo '<td>'.$row['d2'].'</td>';
				echo '<td>'.$row['d3'].'</td>';
				echo '<td>'.$row['date_updated'].'</td>';
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

	echo '</tbody>';
}
