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

function get_page_no($con, $per_page=20, $query="")
{
    if($query != "")
    {
        $query = mysql_real_escape_string($query);
        $where = "AND `sell_skill` LIKE '%{$query}%'";
    }
    else
    {
        $where = "";
    }
    $sql = "SELECT * FROM `listings` WHERE `visible`=1 $where";
    $result = mysql_query($sql, $con);
    $row_count = mysql_num_rows($result);
    if(!is_numeric($row_count))
    {
        return 1;
    }
    else
    {
        if($row_count == 0)
        {
            return 1;
        }
        else
        {
            return (int) ceil($row_count / $per_page);
        }
    }
}

?>
