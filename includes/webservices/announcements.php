<?php

$announcement 			= new Announcement();
$announcement->message 	= "Hi there! Welcome to Snap2Chat! I hope you will have a great experience with the app! Enjoy! :)";

echo json_encode($announcement);

class Announcement
{
	public $message;
}

?>