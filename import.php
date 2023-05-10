<?php
require_once('db_connection.php');

if (isset($_POST['submit'])) {
    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");
    $delimiter = ',';
//The fgetcsv() function reads a line from the file pointer $handle, parses it and returns an array of fields.
//$handle: the file pointer resource that specifies the CSV file to read
//1000: the maximum length of a line to be read from the CSV file
//$delimiter: the delimiter used to separate fields in the CSV file.

//The fgetcsv() function is a built-in PHP function that reads a line from a file 
    $header_row = fgetcsv($handle, 1000, $delimiter); 
    
  
    while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE){
    $next_row = fgetcsv($handle, 1000, $delimiter);  
        
        if (in_array('', $data)) {
            continue; 
        }
        
        $part_sql = "INSERT INTO master_data (ori_part_no, part_no, total_qty, total_amt) VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]')";
        if (mysqli_query($conn, $part_sql)) {
            echo "Record inserted into 'master_data' table successfully<br>";
        } else {
            echo "Error inserting record into 'master_data' table: " . mysqli_error($conn) . "<br>";
        }
        
        for ($i = 4; $i < count($data); $i++) {
            $size = $header_row[$i];
            $qty = $data[$i];
            $price =  $next_row[$i];
            
            if ($qty == "QTY" || $price == "PRICE") {
                continue;
            }
            $trans_sql = "INSERT INTO trans_data (qty, price, size) VALUES ('$qty', '$price', '$size')";
            if (mysqli_query($conn, $trans_sql)) {
                echo "Record inserted into 'trans_data' table successfully<br>";
            } else {
                echo "Error inserting record into 'trans_data' table: " . mysqli_error($conn) . "<br>";
            }
        }
    }

    fclose($handle);
    echo "Import done!";
}

mysqli_close($conn);

header("Location: form.php");
?>
