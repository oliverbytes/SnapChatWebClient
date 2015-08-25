<?php

require_once("../initialize.php");

const OKAY = "okay";
const BAD = "bad";

$response = "";

if(isset($_GET['object']))
{
	if($_GET['object'] == "extendedprofile")
	{
		if(isset($_POST['username']))
		{
			$username = $_POST['username'];

			if(ExtendedProfile::get_by_username($username) == false)
		    {
		      $object = new ExtendedProfile();
		      $object->username = $username;
		      $object->create();

		      $response = $object;
		    }
		    else
		    {
		    	$object = ExtendedProfile::get_by_username($username);

				if($object)
				{
					if($object->picture)
					{
						$theFile = "images/extendedprofiles/2".$object->id.".jpg";

						if(!file_exists($theFile))
				        {
				            file_put_contents($theFile, base64_decode($object->picture));
				        }
				        else
			            {
			              if(filesize($theFile) == 0)
			              {
			              	file_put_contents($theFile, base64_decode($object->picture));
			              }
			            }
				        
						$object->picture = HOST."slir/w100/snapchat/includes/webservices/images/extendedprofiles/2".$object->id.".jpg";
					}
					else
					{
						$object->picture = "http://i.imgur.com/tmqV1Vo.png";
					}

					$response = $object;
				}
				else
				{
					$response = "Username does not exists.";
				}
		    }
		}
		else
		{
			$response = "No username specified.";
		}
	}
	else if($_GET['object'] == "boxsnapcomment")
	{
		$sql = "SELECT * FROM ".T_BOXSNAPCOMMENTS." WHERE boxsnapid = ".$_GET['boxsnapid']. " ORDER BY id DESC";
		$objects = BoxSnapComment::get_by_sql($sql);

		if(count($objects) > 0)
		{
			foreach ($objects as $object)
			{
				// EXTENDED PROFILE PICTURE

				$extendedprofile = ExtendedProfile::get_by_username($object->username);

				$object->name = $extendedprofile->name;

				if($extendedprofile && $extendedprofile->picture)
				{
					$theFile = "images/extendedprofiles/".$extendedprofile->id.".jpg";

					if(!file_exists($theFile))
			        {
			            file_put_contents($theFile, base64_decode($extendedprofile->picture));
			        }
			        else
		            {
		              if(filesize($theFile) == 0)
		              {
		              	file_put_contents($theFile, base64_decode($extendedprofile->picture));
		              }
		            }

					$object->profilepicture = HOST."slir/w100/snapchat/includes/webservices/images/extendedprofiles/".$extendedprofile->id.".jpg";
				}
				else
				{
					$object->profilepicture = "http://i.imgur.com/tmqV1Vo.png";
				}
			}
		}

		$response = $objects;
	}
	else if($_GET['object'] == "wallpostcomment")
	{
		$sql = "SELECT * FROM ".T_WALLPOSTCOMMENTS." WHERE wallpostid = ".$_GET['wallpostid']. " ORDER BY id DESC";
		$objects = WallPostComment::get_by_sql($sql);

		if(count($objects) > 0)
		{
			foreach ($objects as $object)
			{
				// EXTENDED PROFILE PICTURE

				$extendedprofile = ExtendedProfile::get_by_username($object->username);

				if($extendedprofile && $extendedprofile->picture)
				{
					$theFile = "images/extendedprofiles/".$extendedprofile->id.".jpg";

					if(!file_exists($theFile))
			        {
			            file_put_contents($theFile, base64_decode($extendedprofile->picture));
			        }
			        else
		            {
		              if(filesize($theFile) == 0)
		              {
		              	file_put_contents($theFile, base64_decode($extendedprofile->picture));
		              }
		            }

					$object->profilepicture = HOST."slir/w100/snapchat/includes/webservices/images/extendedprofiles/".$extendedprofile->id.".jpg";
				}
				else
				{
					$object->profilepicture = "http://i.imgur.com/tmqV1Vo.png";
				}
			}
		}

		$response = $objects;
	}
	else if($_GET['object'] == "shoutbox")
	{
		$sql = "SELECT * FROM ".T_SHOUTBOXES;
		$objects = ShoutBox::get_by_sql($sql);

		$filename = 0;

		if(count($objects) > 0)
		{
			foreach ($objects as $object)
			{
				$theFile = "images/shoutboxes/".$object->id.".jpg";

				if(!file_exists($theFile))
		        {
		            file_put_contents($theFile, base64_decode($object->picture));
		        }
		        else
	            {
	              if(filesize($theFile) == 0)
	              {
	              	file_put_contents($theFile, base64_decode($object->picture));
	              }
	            }

				$object->picture = HOST."includes/webservices/images/shoutboxes/".$object->id.".jpg";
			}
		}

		$response = $objects;
	}
	else if($_GET['object'] == "boxsnap")
	{
		$sql = "SELECT * FROM ".T_BOXSNAPS." ORDER BY id DESC LIMIT 50";
		$objects = BoxSnap::get_by_sql($sql);

		$filename = 0;

		if(count($objects) > 0)
		{
			foreach ($objects as $object)
			{
				if($object->picture != "")
				{
					$theFile = "images/boxsnaps/".$object->id.".jpg";

					if(!file_exists($theFile))
			        {
			            file_put_contents($theFile, base64_decode($object->picture));
			        }
			        else
		            {
		              if(filesize($theFile) == 0)
		              {
		              	file_put_contents($theFile, base64_decode($object->picture));
		              }
		            }

					$object->picture = HOST."slir/w300/snapchat/includes/webservices/images/boxsnaps/".$object->id.".jpg";
				}

				// EXTENDED PROFILE PICTURE

				$extendedprofile = ExtendedProfile::get_by_username($object->username);

				$object->name = $extendedprofile->name;

				if($extendedprofile && $extendedprofile->picture)
				{
					$theFile = "images/extendedprofiles/".$extendedprofile->id.".jpg";

					if(!file_exists($theFile))
			        {
			            file_put_contents($theFile, base64_decode($extendedprofile->picture));
			        }
			        else
		            {
		              if(filesize($theFile) == 0)
		              {
		              	file_put_contents($theFile, base64_decode($extendedprofile->picture));
		              }
		            }

					$object->profilepicture = HOST."slir/w100/snapchat/includes/webservices/images/extendedprofiles/".$extendedprofile->id.".jpg";
				}
				else
				{
					$object->profilepicture = "http://i.imgur.com/tmqV1Vo.png";
				}

				$object->likes 		= count(LikedBoxSnap::get_all_by_boxsnapid($object->id));
				$object->comments 	= count(BoxSnapComment::get_all_by_boxsnapid($object->id));

				if(isset($_POST['username']))
				{
					if(LikedBoxSnap::exists($object->id, $_POST['username']))
					{
						$object->liked = true;
					}
				}
			}
		}

		$response = $objects;
	}
	else if($_GET['object'] == "wallpost")
	{
		$sql = "SELECT * FROM ".T_WALLPOSTS." WHERE ".C_WALLPOST_TOUSERNAME."='".$_GET['tousername']."' ORDER BY id DESC LIMIT 20";
		$objects = WallPost::get_by_sql($sql);

		$filename = 0;

		if(count($objects) > 0)
		{
			foreach ($objects as $object)
			{
				$theFile = "images/boxsnaps/".$object->id.".jpg";

				if(!file_exists($theFile))
		        {
		            file_put_contents($theFile, base64_decode($object->picture));
		        }
		        else
	            {
	              if(filesize($theFile) == 0)
	              {
	              	file_put_contents($theFile, base64_decode($object->picture));
	              }
	            }

				$object->picture = HOST."includes/webservices/images/wallposts/".$object->id.".jpg";

				// EXTENDED PROFILE PICTURE

				$toextendedprofile = ExtendedProfile::get_by_username($object->tousername);

				if($extendedprofile && $extendedprofile->picture)
				{
					$theFile = "images/extendedprofiles/".$extendedprofile->id.".jpg";

					if(!file_exists($theFile))
			        {
			            file_put_contents($theFile, base64_decode($extendedprofile->picture));
			        }
			        else
		            {
		              if(filesize($theFile) == 0)
		              {
		              	file_put_contents($theFile, base64_decode($extendedprofile->picture));
		              }
		            }

					$object->profilepicture = HOST."slir/w100/snapchat/includes/webservices/images/extendedprofiles/".$extendedprofile->id.".jpg";
				}
				else
				{
					$object->profilepicture = "http://i.imgur.com/tmqV1Vo.png";
				}

				$fromextendedprofile = ExtendedProfile::get_by_username($object->fromusername);

				if($fromextendedprofile && $fromextendedprofile)
				{
					$theFile = "images/extendedprofiles/".$fromextendedprofile->id.".jpg";

					if(!file_exists($theFile))
			        {
			            file_put_contents($theFile, base64_decode($fromextendedprofile->picture));
			        }
			        else
		            {
		              if(filesize($theFile) == 0)
		              {
		              	file_put_contents($theFile, base64_decode($fromextendedprofile->picture));
		              }
		            }

					$object->fromprofilepicture = HOST."includes/webservices/images/extendedprofiles/".$fromextendedprofile->id.".jpg";
				}
			}
		}

		$response = $objects;
	}
	else if($_GET['object'] == "likedboxsnap")
	{
		$sql = "SELECT * FROM ".T_LIKEDBOXSNAPS;
		$objects = likedBoxSnap::get_by_sql($sql);

		$response = $objects;
	}
	else if($_GET['object'] == "theprofiles")
	{
		$objects = ExtendedProfile::get_all();
		$response = "TOTAL: ".count($objects)."<br/><br/>";

		$profiles = "";

		foreach ($objects as $object) 
		{
			$profiles .= $object->username."<br/>";
		}

		$response .= $profiles;
	}
	else
	{
		$response = "Object Specified Does Not Exists";
	}
}
else
{
	$response = "No Create Object Specified";
}

if($response == "")
{
	$response = array();
}

if($_GET['object'] == "theprofiles")
{
	echo $response;
}
else
{
	echo str_replace('\/','/',json_encode($response));
}

class Response
{
  public $message;
  public $snapchatobject;
  public $status = BAD;
}

class SnapChatObject
{
  public $auth_token;
  public $username;
}

?>