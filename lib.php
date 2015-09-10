<?php

function generate_random_string($length = 30)
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function get_listing($id)
{
    global $con;

    $id = mysql_real_escape_string($id);
    $sql = "SELECT * FROM `listings` WHERE `id`=$id;";
    $result = mysql_query($sql, $con);

    $ret = mysql_fetch_assoc($result);
    return $ret;
}

?>
