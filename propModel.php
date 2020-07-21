<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// $conn = mysqli_connect("localhost", "coverage", "antenna", "coverage");


$servername = "localhost";
$username = "coverage";
$password = "antenna";
$dbname = "coverage";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);


if(!$conn){

    echo 'Connection error ' . mysqli_connect_error();
}
//else{
//echo 'connection made';
//}




$result = mysqli_query($conn, "SELECT * FROM perimarea WHERE shapeID = (SELECT MAX(ID) FROM drawings WHERE name = 'antenna')");

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