<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class ShoutBox extends DatabaseObject
{
	protected static $table_name = T_SHOUTBOXES;
	protected static $col_id = C_SHOUTBOX_ID;

	public $id;
	public $name;
	public $picture;
	public $enabled = 1;
	public $datetime;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 			. self::$table_name . " (";
		$sql .= C_SHOUTBOX_NAME			.", ";
		$sql .= C_SHOUTBOX_PICTURE 		.", ";
		$sql .= C_SHOUTBOX_ENABLED 		.", ";
		$sql .= C_SHOUTBOX_DATETIME;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->name) 	."', ";
		$sql .= " '".$db->escape_string($this->picture) ."', ";
		$sql .= " ".$db->escape_string($this->enabled) 	.", ";
		$sql .= " NOW() ";
		$sql .=")";

		if($db->query($sql))
		{
			$this->id = $db->get_last_id();
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	public function update()
	{
		global $db;

		$sql = "UPDATE " 				. self::$table_name . " SET ";
		$sql .= C_SHOUTBOX_NAME 		. "='" . $db->escape_string($this->name) 		. "', ";
		$sql .= C_SHOUTBOX_PICTURE		. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_SHOUTBOX_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_SHOUTBOX_DATETIME 	. "= NOW() ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 			. "";

		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public function delete()
	{
		global $db;
		$sql = "DELETE FROM " . self::$table_name . " WHERE " . self::$col_id . "=" . $this->id . "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	protected static function instantiate($record)
	{
		$this_class = new self;

		$this_class->id 			= $record[C_SHOUTBOX_ID];
		$this_class->name 			= $record[C_SHOUTBOX_NAME];
		$this_class->picture 		= base64_encode($record[C_SHOUTBOX_PICTURE]);
		$this_class->enabled 		= $record[C_SHOUTBOX_ENABLED];
		$this_class->datetime		= $record[C_SHOUTBOX_DATETIME];

		return $this_class;
	}

	public static function exists($name)
	{
		global $db;

		$name = $db->escape_string($name);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " . C_SHOUTBOX_NAME . " = '" . $name . "' ";

		$result = $db->query($sql);

		return ($db->get_num_rows($result) == 1) ? true : false;
	}
}

?>