<?php

namespace Envms\Osseus\Security;

/**
 * Class Cipher
 *
 */
class Cipher
{
    /** @var $nonce - should be stored in session data */
    private $nonce;

    /** @var string $key */
    private $key;

    /**
     * Cipher constructor.
     *
     * @param $key - this should ideally be set within env.json and NEVER publicly shared. If
     *               env.json is not being used, the key can be passed manually
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Encodes a string to a modified Base64 string for easy data transfer
     *
     * @param $string
     *
     * @return mixed|string
     */
    public function encode($string)
    {
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
    public function decode($string)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($data) % 4;

        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }

    /**
     * The preferred method to encrypt data. Securely erases the data passed to sodium after encryption
     *
     * @param $text
     *
     * @return mixed|string
     */
    public function encipher($text)
    {
        $cipher = sodium_crypto_secretbox($text, $this->nonce, $this->key);

        sodium_memzero($text);

        return $this->encode($cipher);
    }

    /**
     * @param $cipher
     *
     * @return string
     */
    public function decipher($cipher)
    {
        $cipher = $this->decode($cipher);
        $cipherText = mb_substr($cipher, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $plaintext = sodium_crypto_secretbox_open(
            $cipherText,
            $this->nonce,
            $this->key
        );

        sodium_memzero($cipherText);

        return rtrim($plaintext, "\0");
    }

    /**
     * @param $nonce
     *
     * @throws \Exception
     *
     * @return string $nonce
     */
    public function setNonce($nonce = false)
    {
        $this->nonce = ($nonce === false) ? random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) : $nonce;

        return $this->nonce;
    }
}