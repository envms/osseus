<?php

namespace Envms\Osseus\Security;

use Envms\Osseus\Utils\Regex;

/**
 * Class Hash
 *
 *
 */
class Hash
{

    const MD5 = 'md5';
    const SHA1 = 'sha1';
    const SHA256 = 'sha256';
    const SHA512 = 'sha512';
    const WHIRLPOOL = 'whirlpool';

    /** @var string $secret */
    protected $secret;

    /**
     * Hash constructor
     *
     * @param string $secret - Normally supplied by env.json file
     * @param Regex  $regex
     */
    public function __construct(string $secret, Regex $regex)
    {
        $this->secret = $secret;
        $this->regex = $regex;
    }

    /**
     * @param mixed  $data
     * @param string $algorithm
     *
     * @return string
     */
    public function data($data, $algorithm = self::SHA256)
    {
        return hash($algorithm, $this->secret . $data);
    }

    /**
     * This password hasher is very often overkill, however it's minimal overhead for more security
     *
     * @param string $password
     * @param string $salt
     * @param string $algorithm
     *
     * @return string
     */
    public function password(string $password, string $salt = '', string $algorithm = self::SHA512)
    {
        // split the password into three equal-sized chunks
        $splitPassword = $this->regex->split($password, Regex::SPLIT_SECTIONS, 3);
        // apply the secret and salt to make the password just a little more secure
        $toHash = $splitPassword[0] . $this->secret . $splitPassword[1] . $salt . $splitPassword[2];
        // hash the combined string
        $toHash = hash($algorithm, $toHash);

        // hash again with a little more salt added
        return hash($algorithm, $salt . $toHash);
    }

}
