<?php

namespace Envms\Osseus\Security;

/**
 * Class Cipher
 *
 */
class Cipher
{

    /** @var $nonce - stored in Session() after initialization | must be identical across all Cipher instances */
    private $nonce;

    /** @var string $secret */
    private $secret;

    /**
     * Cipher constructor.
     *
     * @param $secret - this should only ever be set within env.json and NEVER publicly shared
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Encodes a string to a modified Base64 string for easy data transfer
     *
     * @param $string
     *
     * @return mixed|string
     */
    public function encode($string) {
        $data = base64_encode($string);
        $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);

        return $data;
    }

    /**
     * Decodes the modified Base64 string
     *
     * @param $string
     *
     * @return string
     */
    public function decode($string) {
        $data = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($data) % 4;

        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }

    /**
     * The preferred method to encrypt data. Uses the static initialization vector passed into or generated from setIv()
     *
     * @param $text
     *
     * @return mixed|string
     */
    public function encipher($text) {

        $cipher = sodium_crypto_secretbox($text,self::$nonce,self::key);

        sodium_memzero($text);

        return $this->encode($cipher);
    }

    /**
     * @param $cipher
     *
     * @return string
     */
    public function decipher($cipher) {

        $cipher       = $this->decode($cipher);
        $ciphertext   = mb_substr($cipher,SODIUM_CRYPTO_SECRETBOX_NONCEBYTES,null,'8bit');
        $plaintext    = sodium_crypto_secretbox_open(
            $ciphertext,
            self::$nonce,
            self::key
        );

        sodium_memzero($ciphertext);

        return rtrim($plaintext, "\0");
    }

    /**
     * @param  mixed $iv
     *
     * @return string $iv
     */
    public static function setIv($iv = false) {
        $size     = openssl_cipher_iv_length(self::CIPHER);
        self::$iv = ($iv === false) ? openssl_random_pseudo_bytes($size) : $iv;

        return self::$iv;
    }

    /**
     * @param  mixed $nonce
     *
     * @return string $nonce
     *
     * @throws \Exception
     */
    public static function setNonce($nonce = false) {

        self::$nonce = ($nonce === false) ? random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) : $nonce;

        return self::$nonce;
    }

}