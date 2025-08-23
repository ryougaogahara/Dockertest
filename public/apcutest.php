<?php
$key = 'access_count';

if (apcu_exists($key)) {
    $count = apcu_inc($key);
} else {
    $count = 1 ;
    apcu_add($key, $count);
}

 "あなたは " . $count . " 人目の訪問者です";
?>

