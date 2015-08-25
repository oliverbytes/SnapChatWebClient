<?php

require_once("../initialize.php");

$message = "";

if(isset($_GET['object']))
{
	if(isset($_GET['id']))
	{
	    $id = $_GET['id'];

	    if($_GET['object'] == "extendedprofile")
		{
			$username 		= $_POST['username'];

			$object 		= ExtendedProfile::get_by_username($username);

			$object->name 	= $_POST["name"];
			$object->age 	= $_POST["age"];
			$object->gender = $_POST["gender"];
			$object->about 	= $_POST["about"];

			if(isset($_FILES['picture']))
			{
			  $file             = new File($_FILES['picture']);
			  $object->picture  = $file->data;
			}
			else
			{
				$object->picture  = base64_decode($object->picture);
			}

			$object->update();

			$message = "success";
		}
		else if($_GET['object'] == "shoutbox")
		{
			$object 		= ShoutBox::get_by_id($id);
			$object->name 	= $_POST["name"];

			if(isset($_FILES['picture']))
			{
			  $file             = new File($_FILES['picture']);
			  $object->picture  = $file->data;
			}
			else
			{
				$object->picture  = base64_decode($object->picture);
			}

			$object->update();

			$message = "success";
		}
		else if($_GET['object'] == "boxsnap")
		{
			$object 			= BoxSnap::get_by_id($id);
			$object->username 	= $_POST["username"];
			$object->message 	= $_POST["message"];

			if(isset($_FILES['picture']))
			{
			  	$file             = new File($_FILES['picture']);
			  	$object->picture  = $file->data;
			}
			else
			{
				$object->picture  = base64_decode($object->picture);
			}

			$object->update();

			$message = "success";
		}
		else if($_GET['object'] == "wallpost")
		{
			$object 				= WallPost::get_by_id($id);
			$object->tousername 	= $_POST["tousername"];
			$object->fromusername 	= $_POST["fromusername"];
			$object->message 		= $_POST["message"];

			if(isset($_FILES['picture']))
			{
			  	$file             = new File($_FILES['picture']);
			  	$object->picture  = $file->data;
			}
			else
			{
				$object->picture  = base64_decode($object->picture);
			}

			$object->update();

			$message = "success";
		}
		else if($_GET['object'] == "likedboxsnap")
		{
			$object 			= LikedBoxSnap::get_by_id($id);
			$object->boxsnapid 	= $_POST["boxsnapid"];
			$object->username 	= $_POST["username"];
			$object->update();

			$message = "success";
		}
		else if($_GET['object'] == "boxsnapcomment")
		{
			$object         	= BoxSnapComment::get_by_id($id);
			$object->boxsnapid 	= $_POST["boxsnapid"];
			$object->username 	= $_POST["username"];
			$object->comment 	= $_POST["comment"];
			$object->update();

			$message = "success";
		}
		else if($_GET['object'] == "wallpostcomment")
		{
			$object         	= WallPostComment::get_by_id($id);
			$object->boxsnapid 	= $_POST["wallpostid"];
			$object->username 	= $_POST["username"];
			$object->comment 	= $_POST["comment"];
			$object->update();

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