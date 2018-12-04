<?php
// --------------------------- check ---------------------------------
if(!isset($_POST['action']))die("Refused.");
if(!(isset($_POST['user']) and isset($_POST['passwd']))) die("Unauthorized.");

// --------------------------- init ----------------------------------
// header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "1wsx@QAZ";
$dbname = "smart_lock";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 

// --------------------------- functions -----------------------------
function check_auth(){
    global $db;
    $sql = "SELECT * FROM users WHERE username='". $_POST['user'] . "'";
    $re = $db->query($sql);
    if($re->num_rows > 0) $row = $re->fetch_assoc(); else die("No User!");
    if($row['password'] != $_POST['passwd']) die("Password Wrong.");
}

// --------------------------- Debug ---------------------------------
if(isset($_POST['show']) && $_POST['show'] == 'tabako'){
    $tables = array('users', 'user_lock', 'cmds');

    foreach ($tables as $tab) {
        $sql = "SELECT * FROM ". $tab;
        $re = $db->query($sql);
        if($re->num_rows > 0){
            while($row = $re->fetch_assoc()) {
                var_dump($row);
            }
        }
    }
    die();
}

if($_POST['action'] == 'exe'){
    $sql = $_POST['order'];
    if ($db->query($sql)) {
        echo "Execute Successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $db->error;
    }
}
// --------------------------- Work ----------------------------------
check_auth();

$result = [
    "status" => 0,
    "data" => 0,
];

switch ($_POST['action']) {
    case 'get_user_info':
        $sql = "SELECT * FROM users WHERE username='". $_POST['user'] . "'";
        $re = $db->query($sql);
        $result['data'] = $re->fetch_assoc();
        break;
    
    default:
        die("Unknown.");
        break;
}

echo json_encode($result);
$db->close();