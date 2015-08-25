<?php

if(isset($_GET['show']))
{
	$files = scandir(getcwd());

	foreach ($files as $file) 
	{
		if($file != "." && $file != ".." && !is_dir($file))
		{
			echo "<a href='".$file."'>".$file."</a><br/>";
		}
	}
}
else
{
	echo "Sorry :)";
}

?>