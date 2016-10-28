<?php
namespace Object;

final class Encription
{
    
    private static $key = 'd0a7e7a97b6d5ecd5534b5232611b87cdc23e88f37b63bb2941e2819dc8ca282';
    // Define a 32-byte (64 character) hexadecimal encryption key
    // Note: The same encryption key used to encrypt the data must be used to decrypt the data
    
    // Encrypt Function
    public static function encrypt($encrypt, $key = null)
    {
        
        if ($key === null) {
            $key = self::$key;
        }
        
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        return $encoded;
    }
    
    // Decrypt Function
    public static function decrypt($decrypt, $key = null)
    {
        
        if ($key === null) {
            $key = self::$key;
        }
        
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calcmac!==$mac){ return false; }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }
}

?>