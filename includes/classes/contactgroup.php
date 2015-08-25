<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class ContactGroup extends DatabaseObject
{
	protected static $table_name = T_CONTACTGROUPS;
	protected static $col_id = C_CONTACTGROUP_ID;

	public $id;
	public $username;
	public $name;
	public $picture;
	public $enabled = 1;
	public $datetime;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 					. self::$table_name . " (";
		$sql .= C_CONTACTGROUP_USERNAME			.", ";
		$sql .= C_CONTACTGROUP_NAME				.", ";
		$sql .= C_CONTACTGROUP_PICTURE 			.", ";
		$sql .= C_CONTACTGROUP_ENABLED 			.", ";
		$sql .= C_CONTACTGROUP_DATETIME;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->username) ."', ";
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

		$sql = "UPDATE " 						. self::$table_name . " SET ";
		$sql .= C_CONTACTGROUP_USERNAME 		. "='" . $db->escape_string($this->username) 		. "', ";
		$sql .= C_CONTACTGROUP_NAME 			. "='" . $db->escape_string($this->name) 			. "', ";
		$sql .= C_CONTACTGROUP_PICTURE			. "='" . $db->escape_string($this->picture) 		. "', ";
		$sql .= C_CONTACTGROUP_ENABLED 			. "=" . $db->escape_string($this->enabled) 			. ", ";
		$sql .= C_CONTACTGROUP_DATETIME 		. "= NOW() ";
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

		$this_class->id 			= $record[C_CONTACTGROUP_ID];
		$this_class->username 		= $record[C_CONTACTGROUP_USERNAME];
		$this_class->name 			= $record[C_CONTACTGROUP_NAME];
		$this_class->picture 		= base64_encode($record[C_CONTACTGROUP_PICTURE]);
		$this_class->enabled 		= $record[C_CONTACTGROUP_ENABLED];
		$this_class->datetime		= $record[C_CONTACTGROUP_DATETIME];

		return $this_class;
	}

	public static function exists($name, $username)
	{
		global $db;

		$name = $db->escape_string($name);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " . C_CONTACTGROUP_NAME . " = '" . $name . "' ";
		$sql .= " AND " . C_CONTACTGROUP_USERNAME . " = '" . $username . "' ";

		$result = $db->query($sql);

		return ($db->get_num_rows($result) == 1) ? true : false;
	}
}

?>