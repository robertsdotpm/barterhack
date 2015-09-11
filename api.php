<?php
require_once("recaptchalib.php");
require_once("config.php");
require_once("lib.php");
require_once("globals.php");

//Process API call.
$call = $_GET["call"];
switch($call)
{
    case "delete":
        $listing_id = mysql_real_escape_string($_GET["id"]);
        $password = $_GET["password"];
        $listing = get_listing($listing_id);
        if($listing == FALSE)
        {
            $ret = array("error" => "Listing does not exist.");
            echo(json_encode($ret));
            break;
        }

        if($listing["password"] == $password)
        {
            $sql = "UPDATE `listings` SET `visible`=0 WHERE `id`=$listing_id";
            mysql_query($sql);
        }
        else
        {
            $ret = array("error" => "You don't have permission to do that.");
            echo(json_encode($ret));
            break;
        }

        header("Location: http://www.barterhack.com/");
        break;

    case "reply":
        //Get data.
        $listing_id = $_POST["id"];
        $message = $_POST["message"];
        $message = preg_replace("/[^A-Za-z0-9 @.\/\:]/", '', $message);
        $listing = get_listing($listing_id);
        if($listing == FALSE)
        {
            $ret = array("error" => "Listing does not exist.");
            echo(json_encode($ret));
            break;
        }

        //Check captcha.
        $resp = recaptcha_check_answer($config["recaptcha"]["priv"], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if(!$resp->is_valid)
        {
            $error = "The reCAPTCHA wasn't entered correctly.";
            $ret = array("error" => $error);
            echo(json_encode($ret));
            break;
        }

        mail($listing["email"], "RE: Your Barter Hack listing.", $message, "From: no-reply@barterhack.com\r\n");
        echo("[1]");
        break;

    case "list":
        //Get listings.
        $per_page_unsafe = $config["listings"]["per_page"];
        $per_page = mysql_real_escape_string($per_page_unsafe);
        $query = $_GET["q"];
        $query_unsafe = $_GET["q"];
        if(!empty($query))
        {
            $query = mysql_real_escape_string(strtolower($query));
            $where = "AND `sell_skill` LIKE '%{$query}%'";
        }
        else
        {
            $where = "";
        }

        //Pagination.
        $page_no = $_POST["p"];
        $total_pages = get_page_no($con, $per_page, $query_unsafe);
        if(empty($page_no))
        {
            $page_no = 1;
        }
        if(!is_numeric($page_no))
        {
            $page_no = 1;
        }
        if($page_no > $total_pages)
        {
            $page_no = 1;
        }
        if($page_no < 1)
        {
            $page_no = 1;
        }
        $start = ($page_no - 1) * $per_page;
        $start = mysql_real_escape_string($start);

        $sql = "SELECT * FROM `listings` WHERE `visible`=1 $where ORDER BY `timestamp` DESC LIMIT $start,$per_page";
        $result = mysql_query($sql, $con);
        $rows = array();
        while($row = mysql_fetch_assoc($result)) {
            $listing = array();
            $listing["id"] = htmlspecialchars($row["id"]);
            $listing["sell_skill"] = htmlspecialchars($row["sell_skill"]);
            $listing["buy_skill"] = htmlspecialchars($row["buy_skill"]);
            $listing["title"] = htmlspecialchars($row["title"]);
            $rows[] = $listing;
        }

        echo(json_encode($rows));
        
        break;

    case "view":
        $listing_id = $_POST["id"];
        $listing = get_listing($listing_id);
        if($listing == FALSE)
        {
            $ret = array("error" => "Listing does not exist.");
            echo(json_encode($ret));
        }
        else
        {
            //Make data safe.
            $listing["id"] = htmlspecialchars($listing["id"]);
            $listing["title"] = htmlspecialchars($listing["title"]);
            $listing["description"] = htmlspecialchars($listing["description"]);

            //Return data.
            $ret = array(
                "id" => $listing["id"],
                "title" => $listing["title"],
                "description" => $listing["description"]
            );
            echo(json_encode($ret));
        }

        break;

    case "post":
        //Check email.
        $email = $_POST["email"];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ret = array("error" => "Invalid email");
            echo(json_encode($ret));
            break;
        }
        else
        {
            $email_unsafe = $email;
            $email = mysql_real_escape_string($email_unsafe);
        }

        //Check captcha.
        $resp = recaptcha_check_answer($config["recaptcha"]["priv"], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if(!$resp->is_valid)
        {
            $error = "The reCAPTCHA wasn't entered correctly.";
            $ret = array("error" => $error);
            echo(json_encode($ret));
            break;
        }

        //Sell skill.
        $sell_skill_unsafe = strtolower($_POST["sell_skill"]);
        if(empty($sell_skill_unsafe))
        {
            $ret = array("error" => "You forgot to enter the skill you're offering.");
            echo(json_encode($ret));
            break;
        }
        $sell_skill = mysql_real_escape_string($sell_skill_unsafe);

        //Buy skill.
        $buy_skill_unsafe = strtolower($_POST["buy_skill"]);
        $buy_skill = mysql_real_escape_string($buy_skill_unsafe);
        if(empty($buy_skill))
        {
            $ret = array("error" => "You forgot to enter the skill you're looking for.");
            echo(json_encode($ret));
            break;
        }

        //Title.
        $title_unsafe = $_POST["title"];
        $title = mysql_real_escape_string($title_unsafe);
        if(empty($title))
        {
            $ret = array("error" => "You forgot to enter a listing title.");
            echo(json_encode($ret));
            break;
        }

        //Description.
        $description_unsafe = $_POST["description"];
        $description = mysql_real_escape_string($description_unsafe);
        if(empty($description))
        {
            $ret = array("error" => "You forgot to enter a listing description.");
            echo(json_encode($ret));
            break;
        }

        //Other (OCD.)
        $ip = mysql_real_escape_string($_SERVER["REMOTE_ADDR"]);
        $timestamp = time();
        $twenty_four_hours_ago = $timestamp - 86400;
        $timestamp = mysql_real_escape_string(time());
        $password_unsafe = generate_random_string();
        $password = mysql_real_escape_string($password_unsafe);

        //Have they already posted listings recently?
        $sql = "SELECT * FROM `listings` WHERE `ip`='$ip' AND `timestamp`>$twenty_four_hours_ago;";
        $result = mysql_query($sql, $con);
        $rows = array();
        while($row = mysql_fetch_row($result)) {
            $rows[] = $row;
        }

        //How many listings?
        if($rows != FALSE)
        {
            if(count($rows) >= $config["listings"]["per_day"]){
                $error = "You are limited to posting " . $config["listings"]["per_day"] . " per day!"; 
                $ret = array("error" => $error);
                echo(json_encode($ret));
                break;
            }
        }
        
        //Create new listing.
        $sql = "INSERT INTO `listings` (`email`, `sell_skill`, `buy_skill`, `title`, `description`, `ip`, `timestamp`, `password`, `visible`) VALUES ('$email', '$sell_skill', '$buy_skill', '$title', '$description', '$ip', $timestamp, '$password', 1);";
        mysql_query($sql, $con);
        $insert_id = mysql_insert_id();

        //Send a notification to their email.
        $subject  = "Barter Hack listing #$insert_id created.";
        $message  = "Your new Barter Hack listing has been created. ";
        $message .= "Should you feel the need to delete it please visit: ";
        $message .= "http://www.barterhack.com/api.php?call=delete&password=";
        $message .= urlencode($password_unsafe) . "&id=";
        $message .= urlencode($insert_id) . " in a browser.";
        mail($email_unsafe, $subject, $message, "From: no-reply@barterhack.com\r\n");
        
        echo("[1]");
        break;

    default:
        die("");
        break;
}

//All done.
mysql_close($con);
?>
