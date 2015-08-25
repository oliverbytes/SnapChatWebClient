<?php 

require_once("header.php"); 

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

if($session->is_logged_in())
{
  $user = User::get_by_id($session->userid);

  if($user->enabled == DISABLED)
  {
    header("location: index.php");
  }
}
else
{
  header("location: index.php");
}

?>

<div class="container-fluid">
  <div class="row-fluid">
    <ul class="nav nav-tabs">
      <li class="active"><a id="userstab" href="#users" data-toggle="tab">Users</a></li>
      <li><a id="logstab" href="#logs" data-toggle="tab">Logs</a></li>
    </ul>
    
    <div class="tab-content">
      <div class="tab-pane active" id="users"><?php require_once("public/grids/users.php"); ?></div>
      <div class="tab-pane" id="logs"><?php require_once("public/grids/logs.php"); ?></div>
    </div>

  </div><!--/row-->

  <script>

    $("#userstab").click(function()
    {
      $("#grid_users").trigger("reloadGrid");
    });

    $("#logstab").click(function()
    {
      $("#grid_logs").trigger("reloadGrid");
    });

  </script>
  
<?php require_once("footer.php"); ?>