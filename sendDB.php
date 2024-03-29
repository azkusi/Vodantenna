<?php

//=================================================================================
//                          CONNECTION TO DATABASE

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

//=================================================================================
//PLACING STRING OF DRAWN COORDINATES INTO DATABASE AND CONVERTING TO ARRAY OF INTEGERS

$coordinates = (isset($_POST['coordinates'])) ? $_POST['coordinates'] : 'no name';



$name = 'shapes';

$sql = "INSERT INTO drawings (name, coords) VALUES ('$name', '$coordinates')";

if (mysqli_query($conn, $sql)) {
  echo "New record into drawings created successfully \n";
}

else {
    header("together.html?=notsuccessful");
//   echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


$sql1 = "SELECT * FROM drawings WHERE coords='$coordinates'";

$result1 = $conn->query($sql1);

//find array just added by using 'select ID where coords == $coordinates' place in variable
if ($result1 = $conn->query($sql1)) {
    while($row = $result1->fetch_assoc()) {
        echo "ID is: " . $row["ID"] . "\n";
        $coordID = $row["ID"];
    }
}
else{
    echo "nahh didn't work \n";
}

//convert coordinates array from string of strings to array of integers
$coordArray = array_map('intval', explode(',', $coordinates));

echo $coordArray[0] . "\n";


//=================================================================================
//   FINDING COORDINATES THAT WILL DRAW LARGEST RECTANGLE FROM COORDINATE ARRAY

//find the lowest x value and corresponding lowest y coordinate (bottom left corner)
//find the largest x value and corresponding largest y coordinate (top right corner)
//find the lowest x value and corresponding largest y coordinate (top left corner)
//find the largest x value and corresponding lowest y coordinate (bottom right corner)



//loop through the array just added, using 'select coords where ID == above variable'
$smallestX = [1000000000];
$smallestY = [0];

$largestX = [0];
$largestY = [1000000000];

$smallestXpair = [0];
$smallestYpair = [0];
$largestXpair = [0];
$largestYpair = [0];

for ($x = 0; $x < (count($coordArray) - 2); $x+=2) {
   //find smallest X
    if ($coordArray[$x] < $smallestX[0]){
        //replace smallestX array element with new value
        $smallestX[0] = $coordArray[$x];
    }
    //find largest X
    else if ($coordArray[$x] > $largestX[0]){
        //replace largestX array element with new value
        $largestX[0] = $coordArray[$x];
            }

    else{
        continue;
    }

    }


//find smallestY and largestY
for ($y = 1; $y < (count($coordArray) - 1); $y+=2) {
   //find smallest Y
    if ($coordArray[$y] > $smallestY[0]){
        //replace smallestY array element with new value
        $smallestY[0] = $coordArray[$y];
    }
    //find largest Y
    else if ($coordArray[$y] < $largestY[0]){
        //replace largestY array element with new value
        $largestY[0] = $coordArray[$y];
            }

    else{
        continue;
    }

    }




for ($x = 0; $x < (count($coordArray) - 2); $x+=2) {
   //find smallestXpair
    if ($coordArray[$x] == $smallestX[0]){
        //set smallestXpair
        $smallestXpair[0] = $coordArray[$x + 1];
    }
    //find largestXpair
    else if ($coordArray[$x] == $largestX[0]){
        //set largestXpair
        $largestXpair[0] = $coordArray[$x + 1];
            }

    else{
        continue;
    }

    }



//find coordinate pairs for limiting x/y values
for ($y = 1; $y < (count($coordArray) - 1); $y+=2) {
   //find smallestYpair
    if ($coordArray[$y] == $smallestY[0]){
        //set smallestYpair
        $smallestYpair[0] = $coordArray[$y - 1];
    }
    //find largest Y
    else if ($coordArray[$y] == $largestY[0]){
        //set smallestYpair
        $largestYpair[0] = $coordArray[$y - 1];
            }

    else{
        continue;
    }

    }


//echo "The smallest X is: " . $smallestX[0] . "\n";


//==================================================================================
//         PLACING RECTANGLE 4 COORDINATE CORNERS INTO DATABASE TABLES


//put together coordinates of corners

$LeftCoord = [$smallestX[0], $smallestXpair[0]];
$TopCoord = [$largestYpair[0], $largestY[0]];
$RightCoord = [$largestX[0], $largestXpair[0]];
$BottomCoord = [$smallestYpair[0], $smallestY[0]];

echo "left coordinate is: " . $LeftCoord[0] . "," . $LeftCoord[1] . "\n";
echo "top coordinate is: " . $TopCoord[0] . "," . $TopCoord[1] . "\n";
echo "right coordinate is: " . $RightCoord[0] . "," . $RightCoord[1] . "\n";
echo "bottom coordinate is: " . $BottomCoord[0] . "," . $BottomCoord[1] . "\n";



$LeftCoord = implode(",", $LeftCoord);
$TopCoord = implode(",", $TopCoord);
$RightCoord = implode(",", $RightCoord);
$BottomCoord = implode(",", $BottomCoord);


//calculate area and perimeter

//$area = () * ()
//$perimeter = () + ()

//place coordinates together and place into...
//dimensions table for dimensions of squares, and in perimarea table


$sql3 = "INSERT INTO dimensions (shapeID, TopCoord, RightCoord, BottomCoord, LeftCoord)
VALUES ($coordID, '$TopCoord', '$RightCoord', '$BottomCoord', '$LeftCoord')";

//$sql4 = "INSERT INTO perimarea (perimeter, area) VALUES ('$perimeter', '$area')";

if (mysqli_query($conn, $sql3)) {
  echo "New record into dimensions created successfully \n";
}
else{
echo("nope did not work matey \n");
}


//if (mysqli_query($conn, $sql)) {
//  echo "New record into perimarea created successfully \n";
//}


//now draw on front end this square

mysqli_close($conn);









?>
