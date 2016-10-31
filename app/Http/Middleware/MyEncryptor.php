<?php
/**
 * Created by PhpStorm.
 * User: leonid
 * Date: 31.10.16
 * Time: 17:51
 */

namespace App\Http\Middleware;


class MyEncryptor
{
    private $key;
    private $iv_size;


    public function __construct($key)
    {
        $this->setPassword($key);
        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    }

    public function encryptMessage($message) {
        # create a random IV to use with CBC encoding
        $iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);

        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key,
            $message, MCRYPT_MODE_CBC, $iv);

        # prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;

        # encode the resulting cipher text so it can be represented by a string
        return base64_encode($ciphertext);
    }

    public function decryptMessage($base64_encoded_text) {
        # --- DECRYPTION ---

        $ciphertext_dec = base64_decode($base64_encoded_text);

        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $this->iv_size);

        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $this->iv_size);

        # may remove 00h valued characters from end of plain text
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);


    }

    private function setPassword($key) {
        $key_size =  strlen($key);
        if (!in_array($key_size , [16, 24, 32])) {
            $key = str_pad($key, 32, "\0");
        }
        $this->key = $key;
    }
}