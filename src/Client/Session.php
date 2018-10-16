<?php

namespace Envms\Osseus\Client;

use Envms\Osseus\Security\{Cipher, Hash};

/**
 * Class Session
 *
 * Handles and secures all session data.
 *
 * @todo Inject class dependencies
 */
class Session extends \SessionHandler
{

    /** @var mixed|null|string */
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
        $this->name = (isset($name)) ? $name : ini_get('session.name');

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

        $cipher = new Cipher('');

        $this->assign('cipher.nonce', $cipher->setNonce($this->get('cipher.nonce'))); // if an iv exists within the current session, use it

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

        $cookie = new Cookie(); // we have to remove any data attached to the session cookie
        $cookie->assign($this->name, 0, (time() - 60 * 60 * 24 * 30)); // 1 month earlier to ensure session cookie deletion

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
     * Calls \SessionHandler's read function and deciphers the encrypted session data.
     *
     * @param string $id
     *
     * @return string
     */
    public function read($id)
    {
        return parent::read($id);
    }

    /**
     * Calls \SessionHandler's write function after encrypting the session data. Uses ECB mode
     * to avoid impossible decryption when initialization vector is stored in the encrypted session data.
     *
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
        $lastActivity = $this->get('ssn.lastActivity');

        if ($lastActivity !== false && (time() - $lastActivity) > ($ttl * 60)) {
            $expired = true;
        } else {
            $this->assign('ssn.lastActivity', time());
            $expired = false;
        }

        return $expired;
    }

    /**
     * Performs a match to ensure the same client is requesting the current session
     *
     * @todo support IPv6
     * @return bool
     */
    public function signature()
    {
        $hash = new Hash();
        $signature = $this->get('ssn.signature');

        //if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_FLAG_IPV6) !== false) {}

        $client = $_SERVER['HTTP_USER_AGENT'] . (ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.0.0'));
        $clientSignature = $hash->data($client);

        if ($signature !== false) {
            return $signature === $clientSignature;
        }

        $this->assign('ssn.signature', $clientSignature);

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
    public function assign($name, $value)
    {
        $parsed = explode('.', $name);
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
        $parsed = explode('.', $name);
        $rtn = $_SESSION;

        while ($parsed) {
            $next = array_shift($parsed);
            if (isset($rtn[$next])) {
                $rtn = $rtn[$next];
            } else {
                $rtn = false;
                break;
            }
        }

        return $rtn;
    }

}
