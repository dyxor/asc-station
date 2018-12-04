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
	  var_dump($tab);
	  while($row = $re->fetch_assoc()){
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
    die();
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
    
    case 'get_user_lock':
        $sql = "SELECT * FROM user_lock WHERE username='". $_POST['user'] . "'";
        $re = $db->query($sql);
        if($re->num_rows>0){
	    $result['data'] = [];
            while($row = $re->fetch_assoc()){
                $result['data'][] = $row;
            }
        }else{
            $result['status'] = 1;
            $result['data'] = "No results.";
        }     
        break;

    case 'send_cmd':
        if(!(isset($_POST['cmd_type']) and isset($_POST['tar_lock']) and isset($_POST['tar_user'])))die("Bad command!");
        $_POST['cmd_type'] = ($_POST['cmd_type'] == 'grant'? 1 : 2);
        $sql = "SELECT * FROM user_lock WHERE username='". $_POST['user'] . "' AND lockname='". $_POST['tar_lock']. "'";
        $re = $db->query($sql);
        if($re->num_rows<1){
            $result['status'] = 1;
            $result['data'] = "User has no such lock.";
            break;
        }

        $sql = "INSERT INTO cmds (username, lockname, type, target)VALUES('". $_POST['user']. "','". $_POST['tar_lock']. "',". $_POST['cmd_type']. ",'". $_POST['tar_user']. "')";
        if(!$db->query($sql)){
            $result['status'] = 1;
            $result['data'] = "Send failed.";
        }
        break;

    case 'retrieve_cmd':
        if(!isset($_POST['tar_lock']))die("Bad command!");
        $sql = "SELECT epoch FROM user_lock WHERE lockname='". $_POST['tar_lock']. "'";
        $re = $db->query($sql);
        if($re->num_rows<1){
            $result['status'] = 1;
            $result['data'] = "No such lock.";
            break;
        }
        $row = $re->fetch_assoc();
        $epoch = $row['epoch'];

        $sql = "SELECT id,type,target FROM cmds WHERE lockname='". $_POST['tar_lock']. "' AND id>". $epoch . " ORDER BY id";
        $re = $db->query($sql);
        $result['data'] = [];
        while($row = $re->fetch_assoc()){
            $result['data'][] = $row;
        }
        $result['data'][] = ['id' => -1, 'type'=>3, 'target'=>$_POST['user']];      // activation
        break;

    case 'update_epoch':
        if(!(isset($_POST['epoch']) and isset($_POST['tar_lock'])))die("Bad command!");
        $sql = "SELECT epoch FROM user_lock WHERE lockname='". $_POST['tar_lock']. "'";
        $re = $db->query($sql);
        $row = $re->fetch_assoc();
        $epoch = intval($row['epoch']);
        if($_POST['epoch'] > $epoch){
            $sql = "UPDATE user_lock SET epoch=". $_POST['epoch']. " WHERE lockname='". $_POST['tar_lock']. "'";
            if(!$db->query($sql)){
                $result['status'] = 1;
                $result['data'] = "Update failed.";
            }
        }
        break;

    default:
        die("Unknown.");
        break;
}

echo json_encode($result);
$db->close();
