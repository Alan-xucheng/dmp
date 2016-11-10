<?php
/**
 * * @Name        RAS.php
 * * @Note        RAS加密
 * * @Author      jbxie
 * * @Created     2016年6月21日22:54:19
 * * @Version     v1.0.0
 * */

class RSA 
{
    public function __construct()
    {
        // 判断openssl扩展存在
        extension_loaded('openssl') or die('openssl extension does not exist');
    }
    
    public static function decode($str, $need_base64_decode = TRUE){
        //私钥存放路径
        $private_key_path = SpringConstant::LIBRARY_PATH . "/rsa/private_key.pem";
        //获取私钥
        $private_key = openssl_pkey_get_private('file://' . $private_key_path);
    
        $ciphertext = $str;
        if($need_base64_decode){
            $bin_ciphertext = base64_decode($ciphertext);
        }
        //解密
        if(@openssl_private_decrypt($bin_ciphertext, $plaintext, $private_key, OPENSSL_PKCS1_PADDING)){
            return $plaintext;
        }
        return FALSE;
    }
    
    /**
     * rsa加密
     * @param string $str 原始明文
     * @param string $need_base64_encode 是否需要base64_encode
     * @return string
     */
    public static function encrypt($str, $need_base64_encode = TRUE)
    {
        // 公钥存放路径
        $public_key_path = SpringConstant::LIBRARY_PATH . "/rsa/public_key.pem";
        file_exists($public_key_path) or die('public key file path is not correct');
        // 生成Resource类型的公钥，如果公钥文件内容被破坏，openssl_pkey_get_public函数返回false
        $public_key = openssl_pkey_get_public(file_get_contents($public_key_path));
        $encrypt_data = '';
        if (@openssl_public_encrypt($str, $encrypt_data, $public_key)) 
        {
            // 加密后 可以base64_encode后方便在网址中传输 或者打印  否则打印为乱码
            if ($need_base64_encode) 
            {
                $encrypt_data = base64_encode($encrypt_data);
            }
        } 
        else 
        {
            die('encrypt failed');
        }
        return $encrypt_data;
    }
    
    /**
     * rsa解密
     * @param string $str rsa密文
     * @param string $need_base64_decode 是否需要base64_decode解密
     * @return string
     */
    public static function decrypt($str, $need_base64_decode = TRUE)
    {
        // 公钥存放路径
        $private_key_path = SpringConstant::LIBRARY_PATH . "/rsa/private_key.pem";
        file_exists($private_key_path) or die('private key file path is not correct');
        // 生成Resource类型的密钥，如果密钥文件内容被破坏，openssl_pkey_get_private函数返回false
        $private_key = openssl_pkey_get_private(file_get_contents($private_key_path));
        $decrypt_data = '';
        $encrypt_data = $str;
        if($need_base64_decode)
        {
            $encrypt_data = base64_decode($encrypt_data);
        }
        //解密
        @openssl_private_decrypt($encrypt_data, $decrypt_data, $private_key, OPENSSL_PKCS1_PADDING) or die('decrypt failed'); 
        return $decrypt_data;
    }
}