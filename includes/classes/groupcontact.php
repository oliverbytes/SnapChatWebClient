<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class GroupContact extends DatabaseObject
{
	protected static $table_name = T_GROUPCONTACTS;
	protected static $col_id = C_GROUPCONTACT_ID;

	public $id;
	public $contactgroupid;
	public $username;
	public $name;
	public $picture;
	public $enabled = 1;
	public $datetime;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 					. self::$table_name . " (";
		$sql .= C_GROUPCONTACT_CONTACTGROUPID	.", ";
		$sql .= C_GROUPCONTACT_USERNAME			.", ";
		$sql .= C_GROUPCONTACT_NAME				.", ";
		$sql .= C_GROUPCONTACT_PICTURE 			.", ";
		$sql .= C_GROUPCONTACT_ENABLED 			.", ";
		$sql .= C_GROUPCONTACT_DATETIME;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->contactgroupid) 	."', ";
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
		$sql .= C_GROUPCONTACT_CONTACTGROUPID 	. "='" . $db->escape_string($this->contactgroupid) 	. "', ";
		$sql .= C_GROUPCONTACT_USERNAME 		. "='" . $db->escape_string($this->username) 		. "', ";
		$sql .= C_GROUPCONTACT_NAME 			. "='" . $db->escape_string($this->name) 			. "', ";
		$sql .= C_GROUPCONTACT_PICTURE			. "='" . $db->escape_string($this->picture) 		. "', ";
		$sql .= C_GROUPCONTACT_ENABLED 			. "=" . $db->escape_string($this->enabled) 			. ", ";
		$sql .= C_GROUPCONTACT_DATETIME 		. "= NOW() ";
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

		$this_class->id 			= $record[C_GROUPCONTACT_ID];
		$this_class->contactgroupid = $record[C_GROUPCONTACT_CONTACTGROUPID];
		$this_class->username 		= $record[C_GROUPCONTACT_USERNAME];
		$this_class->name 			= $record[C_GROUPCONTACT_NAME];
		$this_class->picture 		= base64_encode($record[C_GROUPCONTACT_PICTURE]);
		$this_class->enabled 		= $record[C_GROUPCONTACT_ENABLED];
		$this_class->datetime		= $record[C_GROUPCONTACT_DATETIME];

		return $this_class;
	}

	public static function exists($name, $username)
	{
		global $db;

		$name = $db->escape_string($name);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " . C_GROUPCONTACT_NAME . " = '" . $name . "' ";
		$sql .= " AND " . C_GROUPCONTACT_USERNAME . " = '" . $username . "' ";

		$result = $db->query($sql);

		return ($db->get_num_rows($result) == 1) ? true : false;
	}
}

?>