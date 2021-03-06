<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Trade your technical skills for other people's. A free barter service for skilled people to help get stuff done.">
    <link rel="icon" href="favicon.png" sizes="32x32">
    <link rel="stylesheet" href="css/styles.css"/>

    <title>Barter Hack - Time is money</title>

    <script>
var RecaptchaOptions = { theme : 'clean' };
    </script>


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">


    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body onload="doneLoading();">
  <div id="overlay">
    <div id="progstat"></div>
  </div>

    <script src="js/loading.js"></script>    

    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Barter Hack</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#" data-toggle="modal" data-target="#contact">Contact</a></li>
            <li><a href="https://github.com/robertsdotpm/barterhack">Github</a></li>
          </ul>

       <div class="col-sm-3 col-md-4 pull-right">
        <form class="navbar-form" role="search" onsubmit="return search();">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for skills" name="srch-term" id="srch-term" value="<?php echo(htmlspecialchars($_GET["q"])); ?>">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit" id="do_search"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        </form>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<div class="old_recaptcha">
<?php
require_once('recaptchalib.php');
require_once('config.php');
echo recaptcha_get_html($config["recaptcha"]["pub"]);
?>
</div>

<div class="container">






<!-- Intro well -->
<div class="well">
<div class="row">
  <div class="col-md-12 just-centered">
    <h1 class="bold-text">

Trade your technical skills for other people's</h1>
  </div>
</div>
<div class="row">
  <div class="col-md-12 just-centered">
    <div>
      <p class="tagline lead">
        
       A free barter service for skilled people to help get stuff done
        
      </p>
    </div>
<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#new_post" onclick='create();'>
    Trade your skills
</button>

  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Contact me</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                Made by <a href="mailto:matthew@roberts.pm">matthew@roberts.pm</a> with love.
                <br clear="all"><br clear="all">
                Let me know if something breaks :)
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close_view">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="new_post" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Create a new skills listing
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <form role="form">
                  <div class="form-group">
                    <label>Email address</label>
                      <input type="email" class="form-control"
                      id="new_email" placeholder="Enter email"/>
                  </div>
                  <div class="form-group">
                    <label>Skill you can offer</label>
                      <input type="text" class="form-control"
                          id="new_sell_skill" placeholder="Programming" maxlength="29"/>
                  </div>
                  <div class="form-group">
                    <label>Skill you want to receive</label>
                      <input type="text" class="form-control"
                          id="new_buy_skill" placeholder="Graphic Design" maxlength="29"/>
                  </div>
                  <div class="form-group">
                    <label>Listing title</label>
                      <input type="text" class="form-control"
                          id="new_title" placeholder="Looking for super awesome graphics designer!" maxlength="100"/>
                  </div>
                  <div class="form-group">
                    <label>Listing description</label>
                      <textarea class="form-control"
                          id="new_description" placeholder="I'm an excellent programmer and I'm looking to offer my skills in app and web development in exchange for some logo design. Contact me if interested!"></textarea>
                  </div>

                    <div class="new_post_recaptcha"></div>
                </form>
                
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal" id="close_post">
                            Close
                </button>
                <button type="button" class="btn btn-primary" id="do_post">
                    Post listing
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="view_post" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="view_title"></h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <input type="hidden" id="view_id"></input>
                <div id="view_description"></div>
                <form role="form">
                  <div class="form-group">
                    <label for="exampleInputPassword1"></label>
                    <input type="text" class="form-control"
                          id="reply_email" placeholder="Your email address to receive replies on (required.)" maxlength="100"/>
                      <textarea class="form-control" placeholder="That sounds wonderful. Do you have a link to a portfolio I can view?" id="reply_message" id="reply_message"></textarea>
                  </div>
                </form>
                <div class="view_post_recaptcha"></div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close_reply">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="do_reply">
                    Send message
                </button>
            </div>
        </div>
    </div>
</div>

<div id="frontpage-location-listing">

<div class="row">
  <div class="col-md-12">
  <h3>Recent technical skills on offer</h3>

 <table class="table table-striped table-condensed table-list" id="list_main">
</table>

<!-- Larger pagination -->

<ul class="pagination pull-right">
<?php
require("globals.php");
require("lib.php");
$total_pages = get_page_no($con, $config["listings"]["per_page"], $_GET["q"]);
$page_no = $_GET["p"];
$query = urlencode($_GET["q"]);
$pagination_links = 5;
if(!is_numeric($page_no))
{
    $page_no = 1;
}
else
{
    if($page_no > $total_pages)
    {
        $page_no = 1;
    }

    if($page_no < 1)
    {
        $page_no = 1;
    }
}
echo '<input id="current_page" type="hidden" value="' . $page_no . '">';
echo '<ul class="pagination pull-right">';
if($page_no == 1)
{
    echo '<li><a href="#">&laquo;</a></li>';
}
else
{
    $last_page_no = $page_no - 1;
    echo '<li><a href="index.php?p=' . $last_page_no . '&q=' . $query . '">&laquo;</a></li>';
}

$pages_left = ($total_pages - $page_no);
if($pages_left >= $pagination_links)
{
    $pages_left = $pagination_links - 1;
}

//Previous pages (if any.)
$previous_pages = ($pagination_links - $pages_left) - 1;
if($previous_pages)
{
    //Underflow.
    if($page_no - $previous_pages < 1)
    {
        $previous_pages = $page_no - 1;
    }

    for($i = 0; $i < $previous_pages && $previous_pages; $i++)
    {
        $no = ($page_no - $previous_pages) + $i;
        echo '<li><a href="index.php?p=' . $no . '&q=' . $query . '">' . $no . '</a></li>';
    }
}

//The current page.
echo '<li class="active"><a href="#">' . $page_no . '</a></li>';

//Next pages (if any.)
for($i = 1; $i < $pages_left + 1; $i++)
{
    $no = $page_no + $i;
    echo '<li><a href="index.php?p=' . $no . '&q=' . $query . '">' . $no . '</a></li>';
}

if($page_no == $total_pages)
{
    echo '<li><a href="#">&raquo;</a></li>';
}
else
{
    $next_page_no = $page_no + 1;
    echo '<li><a href="index.php?p=' . $next_page_no . '&q=' . $query . '">&raquo;</a></li>';
}

?>

</ul>

<br clear="all">
<div class="spacer"></div>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src="js/notify.js"></script>
<script src="js/ui.js"></script>

<script src='https://www.google.com/recaptcha/api.js'></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
list("<?php echo(htmlspecialchars($_GET["q"])); ?>");

$("#do_post").click(function(){
    $(this).prop('disabled', true);
    $(this).text('Posting ...');
    post();
});

$("#do_reply").click(function(){
    $(this).prop('disabled', true);
    $(this).text('Sending ...');
    reply();
});

$("#close_reply").click(function(){
    $(".old_recaptcha").hide();
});

$("#close_post").click(function(){
    $(".old_recaptcha").hide();
});
</script>
</body>
</html>
