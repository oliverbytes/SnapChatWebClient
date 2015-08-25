<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class BoxSnap extends DatabaseObject
{
	protected static $table_name = T_BOXSNAPS;
	protected static $col_id = C_BOXSNAP_ID;

	public $id;
	public $username;
	public $message;
	public $picture;
	public $enabled = 1;
	public $profilepicture;
	public $datetime;

	public $name 		= "";
	public $status 		= "";
	public $liked 		= false;
	public $likes 		= "";
	public $comments 	= "";

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 			. self::$table_name . " (";
		$sql .= C_BOXSNAP_USERNAME		.", ";
		$sql .= C_BOXSNAP_MESSAGE		.", ";
		$sql .= C_BOXSNAP_PICTURE 		.", ";
		$sql .= C_BOXSNAP_ENABLED 		.", ";
		$sql .= C_BOXSNAP_DATETIME;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->username) 	."', ";
		$sql .= " '".$db->escape_string($this->message) 	."', ";
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

		$sql = "UPDATE " 				. self::$table_name . " SET ";
		$sql .= C_BOXSNAP_USERNAME 		. "='" . $db->escape_string($this->username) 	. "', ";
		$sql .= C_BOXSNAP_MESSAGE 		. "='" . $db->escape_string($this->message) 	. "', ";
		$sql .= C_BOXSNAP_PICTURE		. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_BOXSNAP_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_BOXSNAP_DATETIME 	. "= NOW() ";
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

		$this_class->id 			= $record[C_BOXSNAP_ID];
		$this_class->username 		= $record[C_BOXSNAP_USERNAME];
		$this_class->message 		= $record[C_BOXSNAP_MESSAGE];
		$this_class->picture 		= base64_encode($record[C_BOXSNAP_PICTURE]);
		$this_class->enabled 		= $record[C_BOXSNAP_ENABLED];
		$this_class->datetime		= $record[C_BOXSNAP_DATETIME];

		return $this_class;
	}
}

?>