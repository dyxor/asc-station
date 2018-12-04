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
if(isset($_POST['show']) && $_POST['show'] == 'tabako'){
    $tables = array('users', 'user_lock', 'cmds');

    foreach ($tables as $tab) {
        $sql = "SELECT * FROM ". $tab;
        $re = $db->query($sql);
        if($re->num_rows > 0){
            while($row = $re->fetch_assoc()) {
                pr($row);
            }
        }
        echo "<br><br>";
    }
}

$sql = $_POST['order'];

