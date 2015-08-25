<?php 

require_once("header.php"); 

if($session->is_logged_in())
{
  header("location: cpanel.php");
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

?>
  
<?php require_once("footer.php"); 

echo hash('sha256', "DhjkLmnOP2{}");

?>