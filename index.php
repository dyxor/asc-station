<?php
function log($x){
  echo "<pre>".var_dump($x)."</pre>";
}

log($_SERVER);
log($_GET);
log($_POST);

