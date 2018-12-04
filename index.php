<?php
// --------------------------- functions -----------------------------------
function pr($x){
  echo "<pre>", var_dump($x), "</pre>";
}

// --------------------------- check --------------------------------
if(!isset($_POST['action']))die("Refused.");


// --------------------------- init ----------------------------------
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "1wsx@QAZ";
$dbname = "smart_lock";

$db = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// --------------------------------------------------------------------


$sql = $_POST['order'];

if ($db->query($sql) === TRUE) {
    echo "Execute successfully";
} else {
    echo "Error creating database: " . $conn->error;
}
