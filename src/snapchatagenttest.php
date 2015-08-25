<?php

abstract class SnapchatAgent 
{
  const VERSION = '6.0.2';
  const URL = 'https://feelinsonice-hrd.appspot.com/bq';
  const SECRET = 'iEk21fuwZApXlz93750dmW22pw389dPwOk';
  const STATIC_TOKEN = 'm198sOkJEn37DjqZ32lpRu76xmw288xSQ9';
  const BLOB_ENCRYPTION_KEY = 'M02cnQ51Ji97vwT4';
  const HASH_PATTERN = '0001110111101110001111010101111011010001001110011000110001000110'; // Hash pattern

  public static $CURL_OPTIONS = array(
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_USERAGENT => 'Snapchat/6.0.2 (iPhone6,2; iOS 7.0.4; gzip)',
  );

  public function timestamp() 
  {
    return intval(microtime(TRUE) * 1000);
  }

  public function pad($data, $blocksize = 16) 
  {
    $pad = $blocksize - (strlen($data) % $blocksize);
    return $data . str_repeat(chr($pad), $pad);
  }

  public function decryptECB($data) 
  {
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, self::BLOB_ENCRYPTION_KEY, self::pad($data), MCRYPT_MODE_ECB);
  }

  public function encryptECB($data) 
  {
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, self::BLOB_ENCRYPTION_KEY, self::pad($data), MCRYPT_MODE_ECB);
  }

  public function decryptCBC($data, $key, $iv) 
  {
    // Decode the key and IV.
    $iv = base64_decode($iv);
    $key = base64_decode($key);

    // Decrypt the data.
    $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
    $padding = ord($data[strlen($data) - 1]);

    return substr($data, 0, -$padding);
  }

  public function hash($first, $second) 
  {
    // Append the secret to the values.
    $first = self::SECRET . $first;
    $second = $second . self::SECRET;

    // Hash the values.
    $hash = hash_init('sha256');
    hash_update($hash, $first);
    $hash1 = hash_final($hash);

    $hash = hash_init('sha256');
    hash_update($hash, $second);
    $hash2 = hash_final($hash);

    // Create a new hash with pieces of the two we just made.
    $result = '';

    for ($i = 0; $i < strlen(self::HASH_PATTERN); $i++) 
    {
      $result .= substr(self::HASH_PATTERN, $i, 1) ? $hash2[$i] : $hash1[$i];
    }

    return $result;
  }

  public function post($endpoint, $data, $params, $multipart = FALSE) 
  {
    $ch = curl_init();

    $data['req_token'] = self::hash($params[0], $params[1]);
    //$data['version'] = self::VERSION;

    if (!$multipart)
    {
      $data = http_build_query($data);
    }

    $options = self::$CURL_OPTIONS + array(
      CURLOPT_POST => TRUE,
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_URL => self::URL . $endpoint,
    );

    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);

    // If cURL doesn't have a bundle of root certificates handy, we provide
    // ours (see http://curl.haxx.se/docs/sslcerts.html).
    if (curl_errno($ch) == 60) 
    {
      curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/ca_bundle.crt');
      $result = curl_exec($ch);
    }

    // If the cURL request fails, return FALSE. Also check the status code
    // since the API generally won't return friendly errors.
    if ($result === FALSE || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) 
    {
      curl_close($ch);
      return FALSE;
    }

    curl_close($ch);

    if ($endpoint == '/blob') {
      return $result;
    }

    // Add support for foreign characters in the JSON response.
    $result = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($result));

    $data = json_decode($result);
    return json_last_error() == JSON_ERROR_NONE ? $data : FALSE;
  }

}