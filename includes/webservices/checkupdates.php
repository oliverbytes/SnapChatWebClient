<?php

$currentversion 	= $_GET['version'];
$newestversion 		= "0.9.4.1";

$update 			= new Update();
$update->version 	= $newestversion;	

if($currentversion != $newestversion)
{
	$update->message 	= "Hi, there's a new version update available for Snap2Chat: v" . $newestversion . " Please go to BlackBerry World to update! :) Thank you for using Snap2Chat. Have a nice day!";
}

echo json_encode($update);

class Update
{
	public $version;
	public $message;
}

?>