<?php 

defined('DS') ? null : 				define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : 		define('SITE_ROOT'		, DS.'xampp'.DS.'htdocs'.DS.'snapchat');
defined('DB_SERVER') ? null : 		define("DB_SERVER"		, "localhost");
defined('DB_NAME') ? null : 		define("DB_NAME"		, "wwwkelly_snap2chat");
defined('DB_USER') ? null : 		define("DB_USER"		, "root");
defined('DB_PASS') ? null : 		define("DB_PASS"		, "");
defined('HOSTNAME') ? null : 		define("HOSTNAME"		, "http://192.168.16.112/");
defined('HOST') ? null : 			define("HOST"			, HOSTNAME . "snapchat/");

// defined('SITE_ROOT') ? null : 		define('SITE_ROOT'		, DS.'home'.DS.'wwwkelly'.DS.'public_html'.DS.'droidstore');
// defined('DB_SERVER') ? null : 		define("DB_SERVER"		, "localhost");
// defined('DB_NAME') ? null : 			define("DB_NAME"		, "wwwkelly_droidstore");
// defined('DB_USER') ? null : 			define("DB_USER"		, "wwwkelly_user");
// defined('DB_PASS') ? null : 			define("DB_PASS"		, "DhjkLmnOP2{}");
// defined('HOSTNAME') ? null : 		define("HOSTNAME"		, "http://kellyescape.com/droidstore/");
// defined('HOST') ? null : 			define("HOST"			, "http://kellyescape.com/droidstore/");

defined('INCLUDES_PATH') ? null : 	define('INCLUDES_PATH', SITE_ROOT.DS.'includes');
defined('PUBLIC_PATH') ? null : 	define('PUBLIC_PATH', SITE_ROOT.DS.'public');
defined('CLASSES_PATH') ? null : 	define('CLASSES_PATH', INCLUDES_PATH.DS.'classes');

// HELPERS
require_once(INCLUDES_PATH.DS."config.php");
require_once(INCLUDES_PATH.DS."functions.php");

// CORE PHPS
require_once(CLASSES_PATH.DS."database.php");
require_once(CLASSES_PATH.DS."database_object.php");

// OBJECT PHPS
require_once(CLASSES_PATH.DS."extendedprofile.php");
require_once(CLASSES_PATH.DS."shoutbox.php");
require_once(CLASSES_PATH.DS."boxsnap.php");
require_once(CLASSES_PATH.DS."wallpost.php");
require_once(CLASSES_PATH.DS."likedboxsnap.php");
require_once(CLASSES_PATH.DS."wallpostcomment.php");
require_once(CLASSES_PATH.DS."boxsnapcomment.php");
require_once(CLASSES_PATH.DS."groupcontact.php");
require_once(CLASSES_PATH.DS."contactgroup.php");
require_once(CLASSES_PATH.DS."file.php");

$clientip = $_SERVER['REMOTE_ADDR'];

?>