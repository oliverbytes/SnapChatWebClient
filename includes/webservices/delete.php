<?php

require_once("../initialize.php");

$message = "";

if(isset($_GET['object']))
{
  if(isset($_POST['id']) || isset($_GET['id']))
  {
    if(isset($_POST['id']) && $_POST['id'] != "")
    {
      $id = $_POST['id'];
    }
    else if(isset($_GET['id']) && $_GET['id'] != "")
    {
      $id = $_GET['id'];
    }

    if($_GET['object'] == "shoutbox")
    {
      ShoutBox::get_by_id($id)->delete();

      $message = "success";
    }
    else if($_GET['object'] == "boxsnap")
    {
      BoxSnap::get_by_id($id)->delete();

      $message = "success";
    }
    else if($_GET['object'] == "wallpost")
    {
      WallPost::get_by_id($id)->delete();

      $message = "success";
    }
    else if($_GET['object'] == "likedboxsnap")
    {
      LikedBoxSnap::get_by_id($id)->delete();

      $message = "success";
    }
    else if($_GET['object'] == "wallpostcomment")
    {
      WallPostComment::get_by_id($id)->delete();

      $message = "success";
    }
    else if($_GET['object'] == "boxsnapcomment")
    {
      BoxSnapComment::get_by_id($id)->delete();

      $message = "success";
    }
    else
    {
      $message = "Object Specified Does Not Exists";
    }
  }
  else
  {
    $message = "No Object ID Specified";
  }
}
else
{
  $message = "No Create Object Specified";
}

echo $message;

?>