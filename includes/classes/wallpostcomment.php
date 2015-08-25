<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class WallPostComment extends DatabaseObject
{
	protected static $table_name = T_WALLPOSTCOMMENTS;
	protected static $col_id = C_WALLPOSTCOMMENT_ID;

	public $id;
	public $wallpostid;
	public $username;
	public $comment;
	public $enabled = 1;
	public $datetime;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 					. self::$table_name . " (";
		$sql .= C_WALLPOSTCOMMENT_WALLPOSTID	.", ";
		$sql .= C_WALLPOSTCOMMENT_USERNAME		.", ";
		$sql .= C_WALLPOSTCOMMENT_COMMENT		.", ";
		$sql .= C_WALLPOSTCOMMENT_PICTURE 		.", ";
		$sql .= C_WALLPOSTCOMMENT_ENABLED 		.", ";
		$sql .= C_WALLPOSTCOMMENT_DATETIME;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->wallpostid) 	.", ";
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
		$sql .= C_WALLPOSTCOMMENT_WALLPOSTID 	. "=" . $db->escape_string($this->wallpostid) 	. ", ";
		$sql .= C_WALLPOSTCOMMENT_USERNAME 	. "='" . $db->escape_string($this->username) 	. "', ";
		$sql .= C_WALLPOSTCOMMENT_COMMENT 	. "='" . $db->escape_string($this->comment) 	. "', ";
		$sql .= C_WALLPOSTCOMMENT_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_WALLPOSTCOMMENT_DATETIME 	. "= NOW() ";
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

		$this_class->id 			= $record[C_WALLPOSTCOMMENT_ID];
		$this_class->wallpostid 	= $record[C_WALLPOSTCOMMENT_WALLPOSTID];
		$this_class->username 		= $record[C_WALLPOSTCOMMENT_USERNAME];
		$this_class->comment 		= $record[C_WALLPOSTCOMMENT_COMMENT];
		$this_class->enabled 		= $record[C_WALLPOSTCOMMENT_ENABLED];
		$this_class->datetime		= $record[C_WALLPOSTCOMMENT_DATETIME];

		return $this_class;
	}

	public static function exists($wallpostid, $username)
	{
		global $db;

		$wallpostid = $db->escape_string($wallpostid);
		$username = $db->escape_string($username);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " . C_WALLPOSTCOMMENT_WALLPOSTID . " = " . $wallpostid . " ";
		$sql .= " AND " . C_WALLPOSTCOMMENT_USERNAME . " = '" . $username . "' ";

		$result = $db->query($sql);

		return ($db->get_num_rows($result) == 1) ? true : false;
	}
}

?>