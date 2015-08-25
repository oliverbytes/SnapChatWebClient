<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class WallPost extends DatabaseObject
{
	protected static $table_name = T_WALLPOSTS;
	protected static $col_id = C_WALLPOST_ID;

	public $id;
	public $tousername;
	public $fromusername;
	public $message;
	public $picture;
	public $enabled = 1;
	public $toprofilepicture;
	public $fromprofilepicture;
	public $datetime;

	public function create()
	{
		global $db;

		$sql = "INSERT INTO " 			. self::$table_name . " (";
		$sql .= C_WALLPOST_TOUSERNAME	.", ";
		$sql .= C_WALLPOST_FROMUSERNAME	.", ";
		$sql .= C_WALLPOST_MESSAGE		.", ";
		$sql .= C_WALLPOST_PICTURE 		.", ";
		$sql .= C_WALLPOST_ENABLED 		.", ";
		$sql .= C_WALLPOST_DATETIME;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->tousername) 	."', ";
		$sql .= " '".$db->escape_string($this->fromusername)."', ";
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
		$sql .= C_WALLPOST_TOUSERNAME 	. "='" . $db->escape_string($this->tousername) 	. "', ";
		$sql .= C_WALLPOST_FROMUSERNAME . "='" . $db->escape_string($this->fromusername). "', ";
		$sql .= C_WALLPOST_MESSAGE 		. "='" . $db->escape_string($this->message) 	. "', ";
		$sql .= C_WALLPOST_PICTURE		. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_WALLPOST_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_WALLPOST_DATETIME 	. "= NOW() ";
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

		$this_class->id 			= $record[C_WALLPOST_ID];
		$this_class->tousername 	= $record[C_WALLPOST_TOUSERNAME];
		$this_class->fromusername 	= $record[C_WALLPOST_FROMUSERNAME];
		$this_class->message 		= $record[C_WALLPOST_MESSAGE];
		$this_class->picture 		= base64_encode($record[C_WALLPOST_PICTURE]);
		$this_class->enabled 		= $record[C_WALLPOST_ENABLED];
		$this_class->datetime		= $record[C_WALLPOST_DATETIME];

		return $this_class;
	}
}

?>