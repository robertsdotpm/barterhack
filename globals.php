<?php
require_once("config.php");

//Connect to DB.
$con = mysql_connect($config["db"]["host"], $config["db"]["user"], $config["db"]["pass"]);
if(!$con) {
    die('Not connected : ' . mysql_error());
}

//Select DB.
$db_selected = mysql_select_db($config["db"]["name"], $con);
if(!$db_selected) {
    die('Can\'t use foo : ' . mysql_error());
}

?>
