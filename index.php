<?php
function pr($x){
  echo "<pre>", var_dump($x), "</pre>";
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$name = $_GET['name'];

if($_SERVER['REQUEST_METHOD'] == 'GET') echo json_encode($_GET);
else echo json_encode($_POST);
