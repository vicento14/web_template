<?php
// error_reporting(0);
require '../DatabaseConnections.php';

// Remove UTF-8 BOM
function removeBomUtf8($s){
    if (substr($s,0,3) == chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))) {
        return substr($s,3);
    } else {
        return $s;
    }
}

function check_csv($file, $db) {

    //READ FILE
    $csv_file = fopen($file,'r');

    // SKIP FIRST LINE
    $first_line = fgets($csv_file);

    // Remove UTF-8 BOM from First Line
    $first_line = removeBomUtf8($first_line);

    $hasError = 0; $hasBlankError = 0; $isExistsOnDb = 0; $isDuplicateOnCsv = 0;
    $hasBlankErrorArr = array();
    $isExistsOnDbArr = array();
    $isDuplicateOnCsvArr = array();
    $dup_temp_arr = array();

    $message = "";
    $check_csv_row = 0;

    $first_line = preg_replace('/[\t\n\r]+/', '', $first_line);
    $valid_first_line1 = '"ID Number","Full Name",Username,Password,Section,Role';
    $valid_first_line2 = "ID Number,Full Name,Username,Password,Section,Role";
    if ($first_line == $valid_first_line1 || $first_line == $valid_first_line2) {
        while (($line = fgetcsv($csv_file)) !== false) {
            // Check if the row is blank or consists only of whitespace
            if (empty(implode('', $line))) {
                $check_csv_row++;
                continue; // Skip blank lines
            }

            $check_csv_row++;

            $id_number = $line[0];
            $full_name = $line[1];
            $username = $line[2];
            $password = $line[3];
            $section = $line[4];
            $role = $line[5];
            
            // CHECK IF BLANK DATA
            if ($id_number == '' || $full_name == '' || 
                $username == '' || $password == '' || 
                $section == '' || $role == '') {
                // IF BLANK DETECTED ERROR += 1
                $hasBlankError++;
                $hasError = 1;
                array_push($hasBlankErrorArr, $check_csv_row);
            } 

            // Joining all row values for checking duplicated rows
            $whole_line = join(',', $line);

            // CHECK ROWS IF IT HAS DUPLICATE ON CSV
            if (isset($dup_temp_arr[$whole_line])) {
                $isDuplicateOnCsv = 1;
                $hasError = 1;
                array_push($isDuplicateOnCsvArr, $check_csv_row);
            } else {
                $dup_temp_arr[$whole_line] = 1;
            }

            // Connection Object
            $conn = null;

            // Connection Open
            $connectionArr = $db->connect();

            if ($connectionArr['connected'] == 1) {
                $conn = $connectionArr['connection'];

                // CHECK ROWS IF EXISTS
                $sql = "SELECT id FROM user_accounts 
                        WHERE id_number = ? AND full_name = ? AND username = ? 
                        AND section = ? AND role = ?";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $params = array($id_number, $full_name, $username, $section, $role);
                $stmt->execute($params);
                if ($stmt->rowCount() > 0) {
                    $isExistsOnDb = 1;
                    $hasError = 1;
                    array_push($isExistsOnDbArr, $check_csv_row);
                }
            } else {
                $message = $message . $connectionArr['title'] . " " . $connectionArr['message'];
            }
        
            // Connection Close
            $conn = null;
        }
    } else {
        $message = $message . 'Invalid CSV Table Header. Maybe an incorrect CSV file or incorrect CSV header ';
    }
    
    fclose($csv_file);

    if ($hasError == 1) {
        if ($isExistsOnDb == 1) {
            $message = $message . 'Data Already Recorded on row/s ' . implode(", ", $isExistsOnDbArr) . '. ';
        }
        if ($hasBlankError >= 1) {
            $message = $message . 'Blank Cell/s Exists on row/s ' . implode(", ", $hasBlankErrorArr) . '. ';
        }
        if ($isDuplicateOnCsv == 1) {
            $message = $message . 'Duplicated Record/s on row/s ' . implode(", ", $isDuplicateOnCsvArr) . '. ';
        }
    }
    return $message;
}

$mimes = array(
    'text/x-comma-separated-values', 
    'text/comma-separated-values', 
    'application/octet-stream', 
    'application/vnd.ms-excel', 
    'application/x-csv', 
    'text/x-csv', 
    'text/csv', 
    'application/csv', 
    'application/excel', 
    'application/vnd.msexcel', 
    'text/plain'
);

if (!empty($_FILES['file']['name'])) {

    if (in_array($_FILES['file']['type'], $mimes)) {

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {

            $row_count = count_row($_FILES['file']['tmp_name']);

            $chkCsvMsg = check_csv($_FILES['file']['tmp_name'], $db);

            if ($chkCsvMsg == '') {

                if (($csv_file = fopen($_FILES['file']['tmp_name'], "r")) !== false) {
                    // Connection Object
                    $conn = null;

                    // Connection Open
                    $connectionArr = $db->connect();

                    if ($connectionArr['connected'] == 1) {
                        $conn = $connectionArr['connection'];

                        $error = 0;

                        $isTransactionActive = false;
                        $chunkSize = 250; // Set your desired chunk size

                        try {
                            // Prepare the SQL statement for bulk insert
                            $sql = "INSERT INTO user_accounts (id_number, full_name, username, password, section, role) VALUES ";
                            $values = [];
                            $placeholders = [];

                            fgets($csv_file);  // read one line for nothing (skip header / first row)

                            while (($line = fgetcsv($csvFile)) !== false) {
                                $id_number = $line[0];
                                $full_name = $line[1];
                                $username = $line[2];
                                $password = $line[3];
                                $section = $line[4];
                                $role = $line[5];

                                // Create a temporary array for the current row
                                $currentValues = [
                                    $id_number,
                                    $full_name,
                                    $username,
                                    $password,
                                    $section,
                                    $role
                                ];

                                // Check if the row is blank or consists only of whitespace
                                if (empty(implode('', $currentValues))) {
                                    continue; // Skip blank lines
                                }

                                // Create placeholders for each row
                                $generated_placeholders = implode(',', array_fill(0, count($currentValues), '?'));
                                $placeholders[] = "($generated_placeholders)";

                                // Add current values to the main values array
                                $values = array_merge($values, $currentValues);

                                // Check if we reached the chunk size
                                if (count($placeholders) === $chunkSize) {
                                    // Combine the SQL statement with the placeholders
                                    $sql .= implode(', ', $placeholders);
                                    
                                    // Prepare the statement
                                    $stmt = $conn->prepare($sql);
                                    
                                    // Execute the statement with the values
                                    if (!$stmt->execute($values)) {
                                        $error++;
                                    }

                                    // Reset for the next chunk
                                    $placeholders = [];
                                    $values = [];
                                    $sql = "INSERT INTO user_accounts (id_number, full_name, username, password, section, role) VALUES ";
                                }
                            }

                            // Insert any remaining rows that didn't fill a complete chunk
                            if (!empty($placeholders)) {
                                $sql .= implode(', ', $placeholders);
                                $stmt = $conn->prepare($sql);
                                if (!$stmt->execute($values)) {
                                    $error++;
                                }
                            }

                            if ($error > 0) {
                                if ($isTransactionActive) {
                                    $conn->rollBack();
                                    $isTransactionActive = false;
                                }
                                echo 'Data insertion failed. No. of errors: ' . $error;
                                exit();
                            }

                            $conn->commit();
                            $isTransactionActive = false;
                        } catch (Exception $e) {
                            if ($isTransactionActive) {
                                $conn->rollBack();
                                $isTransactionActive = false;
                            }
                            echo 'Failed. Please Try Again or Call IT Personnel Immediately!: ' . $e->getMessage();
                            exit();
                        }
                    } else {
                        echo $connectionArr['title'] . " " . $connectionArr['message'];
                    }

                    // Connection Close
                    $conn = null;
                } else {
                    echo 'Reading CSV file Failed! Try Again or Contact IT Personnel if it fails again';
                }

            } else {
                echo $chkCsvMsg;
            }

        } else {
            echo 'Upload Failed! Try Again or Contact IT Personnel if it fails again';
        }

    } else {
        echo 'Invalid file format';
    }

} else {
    echo 'Please upload a CSV file';
}
