<?php

function getDb() {
    $database = include 'config.php';
    $database = $database['database'];
    $link = mysqli_connect($database['host'], $database['username'], $database['password']) 
           or die("Connection error: " . mysqli_error($link));
    mysqli_select_db($link, $database['database']);
    mysqli_query ($link, "set character_set_results='utf8'");
    mysqli_query ($link, "set character_set_client='utf8'");
    return $link;   
}

function closeDb($link) {
    mysqli_close($link);
}

?>