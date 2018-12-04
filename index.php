<?php
function pr($x){
  echo "<pre>", var_dump($x), "</pre>";
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$name = $_GET['name'];


$arr = array('a' => 1, 'b' => 2, 'c' => 666, 'd' => 4, 'e' => 5);
echo json_encode($arr);
