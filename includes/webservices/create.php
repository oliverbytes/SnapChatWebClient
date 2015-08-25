<?php

require_once("../initialize.php");

$message = "";

if(isset($_GET['object']))
{
	if($_GET['object'] == "shoutbox")
	{
		$object         = new ShoutBox();
		$object->name 	= $_POST["name"];

		if(isset($_FILES['picture']))
		{
		  $file             = new File($_FILES['picture']);
		  $object->picture  = $file->data;
		}

		$object->create();

		$message = "success";
	}
	else if($_GET['object'] == "boxsnap")
	{
		$object         	= new BoxSnap();
		$object->username 	= $_POST["username"];
		$object->message 	= $_POST["message"];

		if(isset($_FILES['picture']))
		{
		  $file             = new File($_FILES['picture']);
		  $object->picture  = $file->data;
		}

		$object->create();

		$message = "success";
	}
	else if($_GET['object'] == "wallpost")
	{
		$object         		= new WallPost();
		$object->tousername 	= $_POST["tousername"];
		$object->fromusername 	= $_POST["fromusername"];
		$object->message 		= $_POST["message"];

		if(isset($_FILES['picture']))
		{
		  $file             = new File($_FILES['picture']);
		  $object->picture  = $file->data;
		}

		$object->create();

		$message = "success";
	}
	else if($_GET['object'] == "likedboxsnap")
	{
		$object = LikedBoxSnap::exists($_POST["boxsnapid"], $_POST["username"]);

		if(!$object) // like
		{
			$object         	= new LikedBoxSnap();
			$object->boxsnapid 	= $_POST["boxsnapid"];
			$object->username 	= $_POST["username"];
			$object->create();
		}
		else // unlike
		{
			$object = LikedBoxSnap::get_by_id_username($_POST["boxsnapid"], $_POST["username"]);

			if($object)
			{
				$object->delete();
			}
		}

		$message = "success";
	}
	else if($_GET['object'] == "boxsnapcomment")
	{
		$object         	= new BoxSnapComment();
		$object->boxsnapid 	= $_POST["boxsnapid"];
		$object->username 	= $_POST["username"];
		$object->comment 	= $_POST["comment"];
		$object->create();

		$message = "success";
	}
	else if($_GET['object'] == "wallpostcomment")
	{
		$object         	= new WallPostComment();
		$object->boxsnapid 	= $_POST["wallpostid"];
		$object->username 	= $_POST["username"];
		$object->comment 	= $_POST["comment"];
		$object->create();

		$message = "success";
	}
	else
	{
		$message = "Object Specified Does Not Exists";
	}
}
else
{
	$message = "No Create Object Specified";
}

echo $message;

?>