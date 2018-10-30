<?php

namespace Envms\Osseus\Security;

use Envms\Osseus\Utils\Regex;

/**
 * Class Sanitize
 *
 * Any user submitted data that needs cleaning by way of escaping or removing
 * invalid and/or special characters should be passed through the necessary methods within the Sanitize class.
 */
class Sanitize
{
    /** @var mixed - Retains the original form of the data for comparison */
    protected $original;
    /** @var mixed */
    protected $sanitized;
    /** @var Regex */
    protected $regex;

    /**
     * @param $data
     */
    public function __construct($data = '')
    {
        $this->sanitized = $this->original = $data;
        $this->regex = new Regex($this->sanitized);
    }

    /**
     * Set internal data to a new value
     *
     * @param $data
     *
     * @return $this
     */
    public function reset($data)
    {
        if ($data !== null) {
            $this->sanitized = $this->original = $data;
            $this->regex->reset($this->sanitized);
        }

        return $this;
    }

    /**
     * @param mixed $data
     * @param int $flags
     *
     * @return $this
     */
    public function html($data = null, $flags = ENT_COMPAT | ENT_HTML5)
    {
        $this->reset($data);
        $this->sanitized = htmlspecialchars($this->sanitized, $flags);

        return $this;
    }

    /**
     * A basic method to escape all SQL special characters. This should rarely ever need to be used,
     * and instead use PDO prepared statements
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function sql($data = null)
    {
        $this->reset($data);
        $this->sanitized = addcslashes($this->sanitized, "\"'`;_%\\\0\r\n");

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function integer($data = null)
    {
        $this->reset($data);
        $nf = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $this->sanitized = intval($nf->parse($this->sanitized));

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function float($data = null)
    {
        $this->reset($data);
        $nf = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $this->sanitized = $nf->parse($this->sanitized);

        return $this;
    }

    /**
     * Remove any characters that are not alphabetical
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function alpha($data = null)
    {
        $this->reset($data);
        $this->sanitized = $this->regex->replace(Regex::NOT_ALPHA);

        return $this;
    }

    /**
     * Remove any characters that are not alphanumeric
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function alnum($data = null)
    {
        $this->reset($data);
        $this->sanitized = $this->regex->replace(Regex::NOT_ALNUM);

        return $this;
    }

    /**
     * Remove any characters that are not hexadecimal
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function hex($data = null)
    {
        $this->reset($data);
        $this->sanitized = $this->regex->replace(Regex::NOT_HEX);

        return $this;
    }

    /**
     * Remove any characters that are not numeric
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function numeric($data = null)
    {
        $this->reset($data);
        $this->sanitized = $this->regex->replace(Regex::NOT_NUM);

        return $this;
    }

    /**
     * Remove any characters that are not a combination of letters, numbers or underscores
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function word($data = null)
    {
        $this->reset($data);
        $this->sanitized = $this->regex->replace(Regex::NOT_WORD);

        return $this;
    }

    /**
     * Access sanitized data outside of the class instance
     *
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Access sanitized data outside of the class instance
     *
     * @return mixed
     */
    public function getSanitized()
    {
        return $this->sanitized;
    }
}
