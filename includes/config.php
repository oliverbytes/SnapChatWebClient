<?php 

// ----------------------------------------- EXTENDED PROFILE TABLE  ------------------------------------------------------- \\
defined('T_EXTENDEDPROFILES') ? null :				define("T_EXTENDEDPROFILES"					, "extendedprofiles");
defined('C_EXTENDEDPROFILE_ID') ? null : 			define("C_EXTENDEDPROFILE_ID"				, "id");
defined('C_EXTENDEDPROFILE_AGE') ? null : 			define("C_EXTENDEDPROFILE_AGE"				, "age");
defined('C_EXTENDEDPROFILE_GENDER') ? null : 		define("C_EXTENDEDPROFILE_GENDER"			, "gender");
defined('C_EXTENDEDPROFILE_USERNAME') ? null : 		define("C_EXTENDEDPROFILE_USERNAME"			, "username");
defined('C_EXTENDEDPROFILE_NAME') ? null : 			define("C_EXTENDEDPROFILE_NAME"				, "name");
defined('C_EXTENDEDPROFILE_ABOUT') ? null : 		define("C_EXTENDEDPROFILE_ABOUT"			, "about");
defined('C_EXTENDEDPROFILE_PICTURE') ? null : 		define("C_EXTENDEDPROFILE_PICTURE"			, "picture");
defined('C_EXTENDEDPROFILE_ENABLED') ? null : 		define("C_EXTENDEDPROFILE_ENABLED"			, "enabled");
defined('C_EXTENDEDPROFILE_DATETIME') ? null :		define("C_EXTENDEDPROFILE_DATETIME"			, "datetime");

// ----------------------------------------- SHOUT BOX TABLE  ------------------------------------------------------- \\
defined('T_SHOUTBOXES') ? null :			define("T_SHOUTBOXES"				, "shoutboxes");
defined('C_SHOUTBOX_ID') ? null : 			define("C_SHOUTBOX_ID"				, "id");
defined('C_SHOUTBOX_NAME') ? null : 		define("C_SHOUTBOX_NAME"			, "name");
defined('C_SHOUTBOX_PICTURE') ? null : 		define("C_SHOUTBOX_PICTURE"			, "picture");
defined('C_SHOUTBOX_ENABLED') ? null : 		define("C_SHOUTBOX_ENABLED"			, "enabled");
defined('C_SHOUTBOX_DATETIME') ? null :		define("C_SHOUTBOX_DATETIME"		, "datetime");

// ----------------------------------------- BOXSNAPS TABLE  ------------------------------------------------------- \\
defined('T_BOXSNAPS') ? null :				define("T_BOXSNAPS"					, "boxsnaps");
defined('C_BOXSNAP_ID') ? null : 			define("C_BOXSNAP_ID"				, "id");
defined('C_BOXSNAP_USERNAME') ? null : 		define("C_BOXSNAP_USERNAME"			, "username");
defined('C_BOXSNAP_MESSAGE') ? null : 		define("C_BOXSNAP_MESSAGE"			, "message");
defined('C_BOXSNAP_PICTURE') ? null : 		define("C_BOXSNAP_PICTURE"			, "picture");
defined('C_BOXSNAP_ENABLED') ? null : 		define("C_BOXSNAP_ENABLED"			, "enabled");
defined('C_BOXSNAP_DATETIME') ? null :		define("C_BOXSNAP_DATETIME"			, "datetime");

// ----------------------------------------- WALL POSTS TABLE  ------------------------------------------------------- \\
defined('T_WALLPOSTS') ? null :				define("T_WALLPOSTS"				, "wallposts");
defined('C_WALLPOST_ID') ? null : 			define("C_WALLPOST_ID"				, "id");
defined('C_WALLPOST_TOUSERNAME') ? null : 	define("C_WALLPOST_TOUSERNAME"		, "tousername");
defined('C_WALLPOST_FROMUSERNAME') ? null : define("C_WALLPOST_FROMUSERNAME"	, "fromusername");
defined('C_WALLPOST_MESSAGE') ? null : 		define("C_WALLPOST_MESSAGE"			, "message");
defined('C_WALLPOST_PICTURE') ? null : 		define("C_WALLPOST_PICTURE"			, "picture");
defined('C_WALLPOST_ENABLED') ? null : 		define("C_WALLPOST_ENABLED"			, "enabled");
defined('C_WALLPOST_DATETIME') ? null :		define("C_WALLPOST_DATETIME"		, "datetime");

// ----------------------------------------- LIKED BOX SNAPS TABLE  ------------------------------------------------------- \\
defined('T_LIKEDBOXSNAPS') ? null :			define("T_LIKEDBOXSNAPS"			, "likedboxsnaps");
defined('C_LIKEDBOXSNAP_ID') ? null : 		define("C_LIKEDBOXSNAP_ID"			, "id");
defined('C_LIKEDBOXSNAP_BOXSNAPID') ? null :define("C_LIKEDBOXSNAP_BOXSNAPID"	, "boxsnapid");
defined('C_LIKEDBOXSNAP_USERNAME') ? null : define("C_LIKEDBOXSNAP_USERNAME"	, "username");
defined('C_LIKEDBOXSNAP_ENABLED') ? null : 	define("C_LIKEDBOXSNAP_ENABLED"		, "enabled");
defined('C_LIKEDBOXSNAP_DATETIME') ? null :	define("C_LIKEDBOXSNAP_DATETIME"	, "datetime");

// ----------------------------------------- GROUP CONTACTS TABLE  ------------------------------------------------------- \\
defined('T_GROUPCONTACTS') ? null :					define("T_GROUPCONTACTS"				, "groupcontacts");
defined('C_GROUPCONTACT_ID') ? null : 				define("C_GROUPCONTACT_ID"				, "id");
defined('C_GROUPCONTACT_CONTACTGROUPID') ? null : 	define("C_GROUPCONTACT_CONTACTGROUPID"	, "contactgroupid");
defined('C_GROUPCONTACT_USERNAME') ? null : 		define("C_GROUPCONTACT_USERNAME"		, "username");
defined('C_GROUPCONTACT_NAME') ? null : 			define("C_GROUPCONTACT_NAME"			, "name");
defined('C_GROUPCONTACT_PICTURE') ? null : 			define("C_GROUPCONTACT_PICTURE"			, "picture");
defined('C_GROUPCONTACT_ENABLED') ? null : 			define("C_GROUPCONTACT_ENABLED"			, "enabled");
defined('C_GROUPCONTACT_DATETIME') ? null :			define("C_GROUPCONTACT_DATETIME"		, "datetime");

// ----------------------------------------- CONTACT GROUPS TABLE  ------------------------------------------------------- \\
defined('T_CONTACTGROUPS') ? null :					define("T_CONTACTGROUPS"				, "contactgroups");
defined('C_CONTACTGROUP_ID') ? null : 				define("C_CONTACTGROUP_ID"				, "id");
defined('C_CONTACTGROUP_USERNAME') ? null : 		define("C_CONTACTGROUP_USERNAME"		, "username");
defined('C_CONTACTGROUP_NAME') ? null : 			define("C_CONTACTGROUP_NAME"			, "name");
defined('C_CONTACTGROUP_PICTURE') ? null : 			define("C_CONTACTGROUP_PICTURE"			, "picture");
defined('C_CONTACTGROUP_ENABLED') ? null : 			define("C_CONTACTGROUP_ENABLED"			, "enabled");
defined('C_CONTACTGROUP_DATETIME') ? null :			define("C_CONTACTGROUP_DATETIME"		, "datetime");

// ----------------------------------------- BOXSNAP COMMENTS TABLE  ------------------------------------------------------- \\
defined('T_BOXSNAPCOMMENTS') ? null :				define("T_BOXSNAPCOMMENTS"				, "boxsnapcomments");
defined('C_BOXSNAPCOMMENT_ID') ? null : 			define("C_BOXSNAPCOMMENT_ID"			, "id");
defined('C_BOXSNAPCOMMENT_BOXSNAPID') ? null : 		define("C_BOXSNAPCOMMENT_BOXSNAPID"		, "boxsnapid");
defined('C_BOXSNAPCOMMENT_USERNAME') ? null : 		define("C_BOXSNAPCOMMENT_USERNAME"		, "username");
defined('C_BOXSNAPCOMMENT_COMMENT') ? null : 		define("C_BOXSNAPCOMMENT_COMMENT"		, "comment");
defined('C_BOXSNAPCOMMENT_ENABLED') ? null : 		define("C_BOXSNAPCOMMENT_ENABLED"		, "enabled");
defined('C_BOXSNAPCOMMENT_DATETIME') ? null :		define("C_BOXSNAPCOMMENT_DATETIME"		, "datetime");

// ----------------------------------------- WALLPOST COMMENTS TABLE  ------------------------------------------------------- \\
defined('T_WALLPOSTCOMMENTS') ? null :				define("T_WALLPOSTCOMMENTS"				, "wallpostcomments");
defined('C_WALLPOSTCOMMENT_ID') ? null : 			define("C_WALLPOSTCOMMENT_ID"			, "id");
defined('C_WALLPOSTCOMMENT_WALLPOSTID') ? null : 	define("C_WALLPOSTCOMMENT_WALLPOSTID"	, "wallpostid");
defined('C_WALLPOSTCOMMENT_USERNAME') ? null : 		define("C_WALLPOSTCOMMENT_USERNAME"		, "username");
defined('C_WALLPOSTCOMMENT_COMMENT') ? null : 		define("C_WALLPOSTCOMMENT_COMMENT"		, "comment");
defined('C_WALLPOSTCOMMENT_ENABLED') ? null : 		define("C_WALLPOSTCOMMENT_ENABLED"		, "enabled");
defined('C_WALLPOSTCOMMENT_DATETIME') ? null :		define("C_WALLPOSTCOMMENT_DATETIME"		, "datetime");


?>