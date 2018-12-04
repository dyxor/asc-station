<?php
function pr($x){
  echo "<pre>".var_dump($x)."</pre>";
}

pr($_SERVER);
pr($_GET);
pr($_POST);

