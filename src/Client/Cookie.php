<?php

namespace Envms\Osseus\Client;

use Envms\Osseus\Security\Sanitize;

/**
 * Class Cookie
 *
 * Handles and secures all cookie data.
 *
 * @todo Inject class dependencies
 */
class Cookie
{
    /** @var Sanitize */
    protected $sanitize;
    /** @var string */
    protected $path;
    /** @var string */
    protected $domain;
    /** @var bool - Leave this on unless the domain does not implement site-wide https */
    protected $secure = true;
    /** @var bool - Should always be on unless a specific cookie needs to be accessible by the client */
    protected $httponly = true;

    /**
     * @param Sanitize $sanitize
     * @param string   $domain - Can be set to a wildcard or subdomain
     * @param string   $path
     */
    public function __construct(Sanitize $sanitize, string $domain = '', string $path = '/')
    {
        $this->sanitize = $sanitize;
        $this->path = $path;
        $this->domain = $domain;
    }

    /**
     * Sets a cookie, sanitizing the cookie name and value
     *
     * @param string $name
     * @param mixed  $value
     * @param int    $expire - Defaults to 3 months (60 x 60 x 24 x 30 x 3)
     *
     * @return bool
     */

    public function assign(string $name, $value, int $expire = 7776000)
    {
        $name = $this->sanitize->word($name)->getSanitized();
        $value = $this->sanitize->html($value)->getSanitized();

        return setcookie($name, $value, $expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * Sets a cookie, does not perform any sanitation to the value. Use this only when ABSOLUTELY NECESSARY.
     *
     * @param string $name
     * @param mixed  $value
     * @param int    $expire
     *
     * @return bool
     */

    public function rawAssign(string $name, $value, int $expire = 7776000)
    {
        $name = $this->sanitize->word($name)->getSanitized();

        return setrawcookie($name, $value, $expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * Create a "permanent" cookie of 5 years (60 x 60 x 24 x 365 x 5)
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */

    public function permanentAssign($name, $value)
    {
        return $this->assign($name, $value, 157680000);
    }

    /**
     * @param $name
     *
     * @return mixed
     */

    public function get($name)
    {
        return $_COOKIE[$this->sanitize->word($name)->getSanitized()];
    }

    /**
     * @param bool $flag
     */
    public function secureOnly(bool $flag)
    {
        $this->secure = $flag;
    }

    /**
     * @param bool $flag
     */
    public function httpOnly(bool $flag)
    {
        $this->httponly = $flag;
    }
}
