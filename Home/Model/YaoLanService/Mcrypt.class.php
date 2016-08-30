<?php
namespace Home\Model\YaoLanService;
class Mcrypt
{
    private static $key = "nyw6euajYeDEElcA5I3ncQbi8uM0Wsi30T06x08puwE=";
    private static $iv = "y5v8s/N6PHurb/tqcwt4uw==";
    //private static $key = "!AS39(#al*%";
    //private static $iv = "sjA34kd9)_+";

    /**
     * cookie解密
     * @author zhaozhongyi
     * $encryptedData 二进制的密文;
     */
    public static  function Decrypt($encryptedData) {
        if(empty($encryptedData))
        {
            return $encryptedData;
        }
        $encryptedData = base64_decode($encryptedData);
        
        $keyv = base64_decode(self::$key);
        $ivv = base64_decode(self::$iv);
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $keyv, $encryptedData, MCRYPT_MODE_CBC, $ivv);
		//echo "<br/>data = ";
		//print_r($data);
        $data = self::stripPKSC7Padding($data);
		//echo "<br/>data = ";
		//print_r($data);
		return $data;
    }

    /**
     * cookie加密
     * @author zhaozhongyi
     * $encryptedData 需加密字符;
     */
    public static function Ecrypt($encryptedData) {
        if(empty($encryptedData))
        {
            return $encryptedData;
        }
		//echo "<br/>encryptedData = ";
		//echo $encryptedData;
        $encryptedText = self::paddingPKCS7($encryptedData);
		//echo "<br/>encryptedText = ";
		//echo $encryptedText;
        $keyv = base64_decode(self::$key);
        $ivv = base64_decode(self::$iv);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $keyv, $encryptedText, MCRYPT_MODE_CBC, $ivv));
    }

    /**
     * PKSC7解密算法
     */
    private static function stripPKSC7Padding($string){
        if(empty($string))
        {
            return $string;
        }
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if(preg_match("/$slastc{".$slast."}/", $string)){
            $string = substr($string, 0, strlen($string)-$slast);
            return $string;
        } else {
            return false;
        }
    }

    /**
     * PKSC7加密算法
     */
    private static function paddingPKCS7($data)
    {
        if(empty($data))
        {
            return $data;
        }
        $block_size = mcrypt_get_block_size('rijndael-128', 'cbc');
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }
}