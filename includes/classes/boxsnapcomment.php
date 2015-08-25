<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class BoxSnapComment extends DatabaseObject
{
	protected static $table_name = T_BOXSNAPCOMMENTS;
	protected static $col_id = C_BOXSNAPCOMMENT_ID;

	public $id;
	public $boxsnapid;
	public $username;
	public $comment;
	public $enabled = 1;
	public $datetime;

	public $profilepicture;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 					. self::$table_name . " (";
		$sql .= C_BOXSNAPCOMMENT_BOXSNAPID		.", ";
		$sql .= C_BOXSNAPCOMMENT_USERNAME		.", ";
		$sql .= C_BOXSNAPCOMMENT_COMMENT		.", ";
		$sql .= C_BOXSNAPCOMMENT_ENABLED 		.", ";
		$sql .= C_BOXSNAPCOMMENT_DATETIME;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->boxsnapid) 	.", ";
		$sql .= " '".$db->escape_string($this->username) 	."', ";
		$sql .= " '".$db->escape_string($this->comment) 	."', ";
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

		$sql = "UPDATE " 					. self::$table_name . " SET ";
		$sql .= C_BOXSNAPCOMMENT_BOXSNAPID 	. "=" . $db->escape_string($this->boxsnapid) 	. ", ";
		$sql .= C_BOXSNAPCOMMENT_USERNAME 	. "='" . $db->escape_string($this->username) 	. "', ";
		$sql .= C_BOXSNAPCOMMENT_COMMENT 	. "='" . $db->escape_string($this->comment) 	. "', ";
		$sql .= C_BOXSNAPCOMMENT_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_BOXSNAPCOMMENT_DATETIME 	. "= NOW() ";
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

		$this_class->id 			= $record[C_BOXSNAPCOMMENT_ID];
		$this_class->boxsnapid 		= $record[C_BOXSNAPCOMMENT_BOXSNAPID];
		$this_class->username 		= $record[C_BOXSNAPCOMMENT_USERNAME];
		$this_class->comment 		= $record[C_BOXSNAPCOMMENT_COMMENT];
		$this_class->enabled 		= $record[C_BOXSNAPCOMMENT_ENABLED];
		$this_class->datetime		= $record[C_BOXSNAPCOMMENT_DATETIME];

		return $this_class;
	}

	public static function exists($boxsnapid, $username)
	{
		global $db;

		$boxsnapid = $db->escape_string($boxsnapid);
		$username = $db->escape_string($username);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " . C_BOXSNAPCOMMENT_BOXSNAPID . " = " . $boxsnapid . " ";
		$sql .= " AND " . C_BOXSNAPCOMMENT_USERNAME . " = '" . $username . "' ";

		$result = $db->query($sql);

		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function get_all_by_boxsnapid($boxsnapid)
	{
		global $db;
		$boxsnapid = $db->escape_string($boxsnapid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_BOXSNAPCOMMENT_BOXSNAPID . " = " . $boxsnapid;
		
		$result_array = self::get_by_sql($sql);

		return $result_array;
	}
}

?>