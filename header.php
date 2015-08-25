<?php 

ob_start();

require_once("includes/initialize.php");

$message  = "";

if($session->is_logged_in())
{
  $user = User::get_by_id($session->userid);
}

if(isset($_POST['login_submit']))
{
  if(
      isset($_POST['username']) && 
      isset($_POST['password']) && 
      $_POST['username'] !="" && 
      $_POST['password'] !=""
    )
  {
    $user = User::login($_POST['username'], $_POST['password']);

    if($user)
    {
      if($user->enabled == 1)
      {
        $session->login($user);

        $log = new Log($user->id, $clientip, "WEB", "LOGIN SUCCESS"); $log->create();

        if($user->is_super_admin())
        {
          header("location: cpanel.php");
        }
        else
        {
          header("location: cpanel.php");
        }
      }
      else
      {
        $log = new Log(0, $clientip, "WEB", "LOGIN DISABLED"); $log->create();
        $message = "Sorry that you can\'t login right now. <br />Your account has been disabled by the admin for some reason.";
      }
    }
    else
    {
      $log = new Log(0, $clientip, "WEB", "LOGIN INVALID"); $log->create();
      $message = "Wrong username or password.";
    }
  }
  else
  {
    $log = new Log(0, $clientip, "WEB", "LOGIN NOT FILLED"); $log->create();
    $message = "Please enter a username and a password.";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>BB10 Droid Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dream Team PH">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <!--SCRIPTS-->
    <script src="public/js/jquery.js"></script>
    <script> $.ajaxSetup({ cache: false }); </script>
    <script src="public/jqueryui/js/jquery-1.9.1.js"></script>
    <script src="public/jqueryui/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="public/js/i18n/grid.locale-en.js"></script>
    <script src="public/js/jquery.jqGrid.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/bootbox.min.js"></script>
    <script src="public/js/bootstrap-datepicker.js"></script>
    <script src="public/js/less.js"></script>
    <script src="public/js/bootstrap-fileupload.min.js"></script>
    <script src="public/js/contextjs.js"></script>
    <script src="public/js/bootstrap-colorpicker.js"></script>
    <script src="public/js/bootstrap-rowlink.min.js"></script>
    <script src="public/js/jquery.toast.min.js"></script>
    <script src="public/js/jquery.form.min.js"></script>
    <script src="public/js/gmaps.js"></script>
    <script> $.ajaxSetup({ cache: false }); </script>
  <!--STYLES-->
    <link rel="stylesheet" href="public/css/bootstrapui/jquery-ui-1.10.0.custom.css" />
    <link href="public/css/ui.jqgrid.css" rel="stylesheet" media="screen" />
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <link href="public/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="public/css/datepicker.css" rel="stylesheet">
    <link href="public/less/datepicker.less" rel="stylesheet/less" />
    <link href="public/css/bootstrap-fileupload.min.css" rel="stylesheet">
    <link href="public/css/bootstrap-rowlink.min.css" rel="stylesheet">
    <link href="public/css/jquery.toast.min.css" rel="stylesheet">
    <style>

      body 
      {
        background-color: #F2ECE9;
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav 
      {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right 
        {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }

      body.dragging, body.dragging * 
      {
        cursor: move !important;
      }

      .dragged 
      {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
      }

      ol.pagesol li.placeholder 
      {
        position: relative;
        background-color: #E01B5D;
        padding: 1px;
      }
      ol.pagesol li.placeholder:before 
      {
        position: absolute;
      }

      .typeahead_wrapper { display: block; height: 30px; }
      .typeahead_photo { float: left; max-width: 30px; max-height: 30px; margin-right: 5px; }
      .typeahead_labels { float: left; height: 30px; }
      .typeahead_primary { font-size: 15px; }
      .typeahead_secondary { font-size: .8em; margin-top: -5px; }

      ul.typeahead
      {
        width: 300px;
      }

    </style>
  </head>
  <body>
    <div class="navbar navbar navbar-fixed-top">
      <div class="navbar-inner" class="nav-collapse collapse">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php"><img style="height: 20px;" src="public/img/logoimage.png"></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li id="index"><a href="index.php"><i class="icon-large icon-home icon-white"></i> Home</a></li>
            </ul>

            <?php 

              if(!$session->is_logged_in())
              { 
                echo '<form class="navbar-form pull-right" action="#" method="post">
                        <input class="span2" name="username" id="username" type="text" placeholder="username">
                        <input class="span2" name="password" id="password" type="password" placeholder="password">
                        <button type="submit" name="login_submit" class="btn">Login</button>
                      </form>'; 
              }
              else
              {
                echo '
                    <ul class="nav">
                      <li id="index"><a href="cpanel.php"><i class="icon-large icon-lock icon-white"></i> Control Panel</a></li>
                      <li id="createdropdown" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <i class="icon-large icon-file icon-white"></i> Create
                          <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                          <li><a href="createfeatureditem.php"><i class="icon-large icon-leaf"></i> Featured App</a></li>
                          <li><a href="createuser.php"><i class="icon-large icon-user"></i> User</a></li>
                        </ul>
                      </li>
                    </ul>
                    
                    <ul class="nav pull-right">  
                      <li class="dropdown">  
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                          '. $user->get_full_name() .'
                          <b class="caret"></b> 
                        </a>  
                        <ul class="dropdown-menu">  
                        <li><a href="updateuser.php?id='.$session->userid.'"><i class="icon-large icon-user"></i> Profile</a></li>
                        <li><a href="public/functions/logout.php"><i class="icon-large icon-off"></i> Logout</a></li>  
                        </ul>  
                      </li>  
                    </ul> 
                    ';
              }

            ?>

            <form class="navbar-search">
              <input id="search" type="text" class="search-query span2" placeholder="Search" data-provide="typeahead" autocomplete="off">
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>