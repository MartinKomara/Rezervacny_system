<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:# */
class Crypto
{
	function encrypt($string) 
	{ 
		$key = 'password to (en/de)crypt';
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
	} 

	function decrypt($string) 
	{ 
		$key = 'password to (en/de)crypt';
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	} 

}
?>
