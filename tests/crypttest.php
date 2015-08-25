<?php

require_once("../src/snapchat.php");
require_once("../includes/initialize.php");

ini_set('max_execution_time', 300);

$snapchat = new Snapchat();
$snapchat->username = "nemoryoliver";
$snapchat->auth_token = "63cd02e8-fa66-47fe-aa8a-a8506cee98d7";

// $media_key 	= "8dZ3pPQ1pmGapkKpCmigaWfGvNP6xbypLtYs3vE8F4A=";
// $media_iv 	= "euCM4bHAmJ+rQb3qQ2atKQ==";

//$rawdata = file_get_contents("snapphoto.jpg");

// $snapphoto = $snapchat->getMedia("440156392293496400r");
// file_put_contents("snapphoto.jpg", $snapphoto);

$snapvideo = $snapchat->getMedia("544776392143843260r");

var_dump($snapvideo);

file_put_contents("snapvideo.mp4", $snapvideo);

// $snapvideozipped = $snapchat->getMedia("521616392165607400r");
// file_put_contents("snapvideozipped.zip", $snapvideozipped);

// ------------------------------------------------------------------

// $storyphoto = $snapchat->getStory("5205060068835328", "8dZ3pPQ1pmGapkKpCmigaWfGvNP6xbypLtYs3vE8F4A=", "euCM4bHAmJ+rQb3qQ2atKQ==");
// file_put_contents("storyphoto.jpg", $storyphoto);

// $storyvideo = $snapchat->getStory("5915908093509632", "9qVBC5\/1LJxsuFuANq5h27aByu5NyURCTpld68XGzss=", "I3k5OXNyvKqNFdBTeiuRvw==");
// file_put_contents("storyvideo.mp4", $storyvideo);

// $storyvideozipped = $snapchat->getStory("6735908270243840", "vAxqvg1oPn4yAp3/F9+OPA==", "Qai/R6f0/qpoXttZD26BKtGC4LRMis+JOoCgg4w9gNs=");
// file_put_contents("storyvideozipped.zip", $storyvideozipped);

//echo "RAW DATA: ".$rawdata."<br/><br/>";

// file_put_contents("rawstoryphotoencrypted.jpg", $rawdata);

// ------------------------

//$encryptedblobdata = encryptECB($rawdata);

//echo "ENCRYPTED: ".$encryptedblobdata."<br/><br/>";

// ------------------------

//$decrypedblobdata = decryptECB($rawdata);

// $decrypedblobdata = decryptCBC($rawdata, $media_key, $media_iv);

// echo "DECRYPTED: ".$decrypedblobdata."<br/><br/>";

 //file_put_contents("snapphotodecrypted.jpg", $decrypedblobdata);

// ------------------------

// if(isMedia(substr($decrypedblobdata, 0, 2)))
// {
// 	echo "IS MEDIA TRUE"."<br/><br/>";
// }
// else
// {
// 	echo "IS MEDIA FALSE"."<br/><br/>";
// }

// ------------------------

//$data - The blob data
function decryptECB($data) 
{
	$BLOB_ENCRYPTION_KEY 	= 'M02cnQ51Ji97vwT4';
	return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $BLOB_ENCRYPTION_KEY, pad($data), MCRYPT_MODE_ECB);
}

function encryptECB($data) 
{
	$BLOB_ENCRYPTION_KEY 	= 'M02cnQ51Ji97vwT4';
	return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $BLOB_ENCRYPTION_KEY, pad($data), MCRYPT_MODE_ECB);
}

function pad($data, $blocksize = 16) 
{
	$pad = $blocksize - (strlen($data) % $blocksize);
	return $data . str_repeat(chr($pad), $pad);
}

// FOR STORIES
function decryptCBC($data, $key, $iv) 
{
	// Decode the key and IV.
	$iv = base64_decode($iv);
	$key = base64_decode($key);

	// Decrypt the data.
	$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	$padding = ord($data[strlen($data) - 1]);

	return substr($data, 0, -$padding);
}

//$data - The blob data (or just the header).
function isMedia($data) 
{
	// Check for a JPG header.
	if ($data[0] == chr(0xFF) && $data[1] == chr(0xD8)) 
	{
	  return TRUE;
	}

	// Check for a MP4 header.
	if ($data[0] == chr(0x00) && $data[1] == chr(0x00)) 
	{
	  return TRUE;
	}

	// I DID THIS FORCE IT AS MEDIA
	return FALSE;
}

function timestamp() 
{
	return intval(microtime(TRUE) * 1000);
}

?>