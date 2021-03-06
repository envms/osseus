<?php

namespace Envms\Osseus\Client;

use Envms\Osseus\Security\{Cipher, Hash, Sanitize};
use Envms\Osseus\Utils\Net;
/**
 * Class Session
 *
 * Handles and secures all session data.
 */
class Session extends \SessionHandler
{
    /** @var string|null */
    protected $name;

    /** @var Cipher */
    public $cipher;
    /** @var Hash */
    public $hash;

    /**
     * Session constructor
     *
     * @param Cipher $cipher
     * @param Hash   $hash
     * @param string $name
     */
    public function __construct(Cipher $cipher, Hash $hash, ?string $name = null)
    {
        $this->name = ($name !== null) ? $name : ini_get('ssn:name');

        session_name($this->name);

        $this->cipher = $cipher;
        $this->hash = $hash;
    }

    /**
     * Starts a new or continues a current session with a 10% chance of regenerating the ID
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function start()
    {
        $started = false;
        if (session_status() === PHP_SESSION_NONE) {
            if (session_start()) {
                $started = (random_int(1, 10) === 7) ? $this->refresh() : true; // lucky 7!
            }
        }

        // if a nonce exists within the current session, use it
        $this->set('cipher:nonce', $this->cipher->setNonce($this->get('cipher:nonce')));

        return $started;
    }

    /**
     * @return bool
     */
    public function forget()
    {
        if (session_status() === PHP_SESSION_NONE) {
            return true; // no session to kill in the first place, so return success
        }

        $_SESSION = [];

        $sanitize = new Sanitize();
        $cookie = new Cookie($sanitize); // we have to remove any data attached to the session cookie

        // 1 month earlier to ensure session cookie deletion
        $cookie->assign($this->name, 0, (time() - 60 * 60 * 24 * 30));

        return session_destroy();
    }

    /**
     * Regenerates the ID, in case of expired session or the 1 in 10 chance this triggers for security
     *
     * @return bool
     */
    public function refresh()
    {
        return session_regenerate_id(true);
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function read($id)
    {
        return parent::read($id);
    }

    /**
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function write($id, $data)
    {
        return parent::write($id, $data);
    }

    /**
     * Performs a check to see when the last client request was versus the current time.
     *
     * @param int $ttl - Time-to-live in minutes
     *
     * @return bool
     */
    public function expired($ttl = 30)
    {
        $lastActivity = $this->get('ssn:lastActivity');

        if ($lastActivity !== false && (time() - $lastActivity) > ($ttl * 60)) {
            return true;
        } else {
            $this->set('ssn:lastActivity', time());
            return false;
        }
    }

    /**
     * Performs a match to ensure the same client is requesting the current session
     *
     * @return bool
     */
    public function signature()
    {
        $signature = $this->get('ssn:signature');

        $client = $_SERVER['HTTP_USER_AGENT'] . Net::getIpSubnet($_SERVER['REMOTE_ADDR']);
        $clientSignature = $this->hash->data($client);

        if ($signature !== false) {
            return $signature === $clientSignature;
        }

        $this->set('ssn:signature', $clientSignature);

        return true;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return !$this->expired() && $this->signature();
    }

    /**
     * Assigns or updates a session value. For "true/false" values use 1 and 0,
     * and not true and false! get() returns bool false if a value is not found.
     *
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $parsed = explode(':', $name);
        $session = &$_SESSION;

        while (count($parsed) > 1) {
            $next = array_shift($parsed);

            if (!isset($session[$next]) || !is_array($session[$next])) {
                $session[$next] = [];
            }

            $session = &$session[$next];
        }

        $session[array_shift($parsed)] = $value;
    }

    /**
     * Accepts a string value separated by . to signify each level of a nested array.
     *
     * @param  string $name
     *
     * @return null
     */
    public function get(string $name)
    {
        $parsed = explode(':', $name);
        $sessionValue = $_SESSION;

        while ($parsed) {
            $next = array_shift($parsed);

            if (isset($sessionValue[$next])) {
                $sessionValue = $sessionValue[$next];
            } else {
                $sessionValue = false;
                break;
            }
        }

        return $sessionValue;
    }
}
