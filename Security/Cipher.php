<?php

namespace Envms\Osseus\Security;

/**
 * Class Cipher
 *
 * @todo Refactor to use OpenSSL instead of now-deprecated Mcrypt
 */
class Cipher {

    /** @const CIPHER int */
    const CIPHER = MCRYPT_RIJNDAEL_256;
    /** @const MODE int */
    const MODE = MCRYPT_MODE_CBC;

    // TODO figure out a way of not making this static, and using dependency injection instead
    /** @var string - stored in Session() after initialization | must be identical across all Cipher instances */
    private static $iv;

    /**
     * Cipher constructor.
     *
     * @param string $secret - this should only ever be set within env.json and NEVER publicly shared
     */
    public function __construct(string $secret) {

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
        $cipher = mcrypt_encrypt(self::CIPHER, self::SECRET, $text, self::MODE, self::$iv);

        return $this->encode($cipher);
    }

    /**
     * @param $cipher
     *
     * @return string
     */
    public function decipher($cipher) {
        $cipher = $this->decode($cipher);
        $text   = mcrypt_decrypt(self::CIPHER, self::SECRET, $cipher, self::MODE, self::$iv);

        return rtrim($text, "\0");
    }

    /**
     * @param $text
     *
     * @return mixed|string
     *
     * @note Use only when use of an initialization vector is not possible
     */
    public function ecbEncipher($text) {
        $cipher = mcrypt_encrypt(self::CIPHER, self::SECRET, $text, MCRYPT_MODE_ECB);

        return $this->encode($cipher);
    }

    /**
     * @param $cipher
     *
     * @return string
     */
    public function ecbDecipher($cipher) {
        $cipher = $this->decode($cipher);
        $text   = mcrypt_decrypt(self::CIPHER, self::SECRET, $cipher, MCRYPT_MODE_ECB);

        return rtrim($text, "\0");
    }

    /**
     * @param  mixed $iv
     *
     * @return string $iv
     */
    public static function setIv($iv = false) {
        $size     = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        self::$iv = ($iv === false) ? mcrypt_create_iv($size) : $iv;

        return self::$iv;
    }

}