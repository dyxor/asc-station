<?php
// ---------------------------- functions -----------------------------------
function pr($x){
  echo "<pre>", var_dump($x), "</pre>";
}

// --------------------------- check --------------------------------
if(!isset($_POST['action']))die("Refused.");


// --------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "1wsx@QAZ";

$db = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";