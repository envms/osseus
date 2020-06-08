<?php

namespace Envms\Osseus\Security;

/**
 * Class Hash
 */
class Hash
{
    const GOST = 'gost';
    const HAVAL256 = 'haval256,5';
    const MD5 = 'md5';
    const SHA1 = 'sha1';
    const SHA256 = 'sha256';
    const SHA3_256 = 'sha3-256';
    const SHA3_512 = 'sha3-512';
    const WHIRLPOOL = 'whirlpool';

    const PASS_BCRYPT = '2y';
    const PASS_ARGON2I = 'argon2i';
    const PASS_ARGON2ID = 'argon2id';

    /** @var string $secret */
    protected $secret;

    /**
     * Hash constructor
     *
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param mixed  $data
     * @param string $algorithm
     *
     * @return string
     */
    public function data($data, $algorithm = self::SHA3_256)
    {
        return hash($algorithm, $this->secret . $data);
    }
}
