<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class ExtendedProfile extends DatabaseObject
{
	protected static $table_name = T_EXTENDEDPROFILES;
	protected static $col_id = C_EXTENDEDPROFILE_ID;

	public $id;
	public $age = 0;
	public $gender = "Unspecified";
	public $username;
	public $name = "(Full Name)";
	public $about = "(About)";
	public $picture;
	public $enabled = 1;
	public $datetime;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 					. self::$table_name . " (";
		$sql .= C_EXTENDEDPROFILE_AGE			.", ";
		$sql .= C_EXTENDEDPROFILE_GENDER		.", ";
		$sql .= C_EXTENDEDPROFILE_USERNAME		.", ";
		$sql .= C_EXTENDEDPROFILE_NAME			.", ";
		$sql .= C_EXTENDEDPROFILE_ABOUT			.", ";
		$sql .= C_EXTENDEDPROFILE_PICTURE 		.", ";
		$sql .= C_EXTENDEDPROFILE_ENABLED 		.", ";
		$sql .= C_EXTENDEDPROFILE_DATETIME;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->age) 		."', ";
		$sql .= " '".$db->escape_string($this->gender) 		."', ";
		$sql .= " '".$db->escape_string($this->username) 	."', ";
		$sql .= " '".$db->escape_string($this->name) 		."', ";
		$sql .= " '".$db->escape_string($this->about) 		."', ";
		$sql .= " '".$db->escape_string($this->picture) 	."', ";
		$sql .= " ".$db->escape_string($this->enabled) 		.", ";
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
		$sql .= C_EXTENDEDPROFILE_AGE 			. "='" . $db->escape_string($this->age) 		. "', ";
		$sql .= C_EXTENDEDPROFILE_GENDER 		. "='" . $db->escape_string($this->gender) 		. "', ";
		$sql .= C_EXTENDEDPROFILE_USERNAME 		. "='" . $db->escape_string($this->username) 	. "', ";
		$sql .= C_EXTENDEDPROFILE_NAME 			. "='" . $db->escape_string($this->name) 		. "', ";
		$sql .= C_EXTENDEDPROFILE_ABOUT 		. "='" . $db->escape_string($this->about) 		. "', ";
		$sql .= C_EXTENDEDPROFILE_PICTURE		. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_EXTENDEDPROFILE_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_EXTENDEDPROFILE_DATETIME 		. "= NOW() ";
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

		$this_class->id 			= $record[C_EXTENDEDPROFILE_ID];
		$this_class->age 			= $record[C_EXTENDEDPROFILE_AGE];
		$this_class->gender 		= $record[C_EXTENDEDPROFILE_GENDER];
		$this_class->username 		= $record[C_EXTENDEDPROFILE_USERNAME];
		$this_class->name 			= $record[C_EXTENDEDPROFILE_NAME];
		$this_class->about 			= $record[C_EXTENDEDPROFILE_ABOUT];
		$this_class->picture 		= base64_encode($record[C_EXTENDEDPROFILE_PICTURE]);
		$this_class->enabled 		= $record[C_EXTENDEDPROFILE_ENABLED];
		$this_class->datetime		= $record[C_EXTENDEDPROFILE_DATETIME];

		return $this_class;
	}

	public static function exists($username)
	{
		global $db;

		$username = $db->escape_string($username);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " . C_EXTENDEDPROFILE_USERNAME . " = '" . $username . "' LIMIT 1 ";

		$result = $db->query($sql);

		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function get_by_username($username)
	{
		global $db;
		$username = $db->escape_string($username);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_EXTENDEDPROFILE_USERNAME . " = '" . $username . "'";
		$sql .= " LIMIT 1";
		
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public function picture()
	{
		return $this->picture;
	}
}

?>