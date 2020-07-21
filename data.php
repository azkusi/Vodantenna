<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$dbname = 'vodamodel';
$dbhost = '';
$dbusername = '';
$dbpassword = '';

foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
        continue;
    }
    
    $dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}

$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

// This is for debugging only, to confirm whether the database connection is successfull.
//echo "<p>Success: A working connection to MySQL was made!</p>";
//echo "<p>The database is: $dbname</p>";
//echo "<p>The host is: = $dbhost</p>";
//echo "<p>Host information: " . mysqli_get_host_info($conn) . "</p>";
//echo "<p>The database username is: $dbusername</p>";



$result = mysqli_query($conn, "SELECT * FROM dimensions WHERE shapeID = (SELECT MAX(shapeID) FROM dimensions)");

$data = array();
while ($row = mysqli_fetch_object($result))
{
    //array_push($data, $row);
    echo  json_encode($row);
}

//echo $data;
//echo  json_encode($data);






mysqli_close($conn);


// $q = $_POST['str'];







?>
