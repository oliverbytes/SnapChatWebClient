<?php 

require_once("initialize.php");

function redirect_to($location = NULL)
{
	if($location != NULL)
	{
		header("Location: {$location}");
		exit();
	}
}

function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$path = INCLUDES_PATH.DS."{$class_name}.php";

	if(file_exists($path))
	{
		require_once($path);
	}
	else
	{
		die("The file {$path} could not be found.");	
	}
}

?>