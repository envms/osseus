<?php

namespace Envms\Osseus\Security;

use Envms\Osseus\Utils\Regex;

/**
 * Class Sanitize
 *
 * Any user submitted data that needs cleaning by way of escaping or removing
 * invalid and/or special characters should be passed through the necessary methods within the Sanitize class.
 */
class Sanitize {

    /** @var mixed - Retains the original form of the data for comparison */
    protected $original;
    /** @var mixed */
    protected $sanitized;
    /** @var Regex */
    protected $regex;

    /**
     * @param $data
     */
    public function __construct($data) {
        $this->sanitized = $this->original = $data;
        $this->regex     = new Regex($this->sanitized);
    }

    /**
     * Set internal data to a new value.
     *
     * @param  $data
     *
     * @return $this
     */

    public function reset($data) {
        $this->sanitized = $this->original = $data;
        $this->regex->reset($this->sanitized);

        return $this;
    }

    /**
     * Access protected sanitized data outside the class
     *
     * @return mixed
     */

    public function getSanitized() {
        return $this->sanitized;
    }

    /**
     * @param int $flags
     *
     * @return Sanitize
     */

    public function html($flags = ENT_COMPAT | ENT_HTML5) {
        $this->sanitized = htmlspecialchars($this->sanitized, $flags);

        return $this;
    }

    /**
     * A basic method to escape all SQL special characters. This should rarely ever need to be used,
     * and instead use PDO prepared statements
     *
     * @return Sanitize
     */
    public function sql() {
        $this->sanitized = addcslashes($this->sanitized, "\"'`;_%\\\0\r\n");

        return $this;
    }

    /**
     * @return Sanitize
     */
    public function integer() {
        $nf              = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $this->sanitized = intval($nf->parse($this->sanitized));

        return $this;
    }

    /**
     * @return Sanitize
     */
    public function float() {
        $nf              = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $this->sanitized = $nf->parse($this->sanitized);

        return $this;
    }

    /**
     * Remove any characters that are not alphabetical
     *
     * @return Sanitize
     */
    public function alpha() {
        $this->sanitized = $this->regex->replace(Regex::NOT_ALPHA);

        return $this;
    }

    /**
     * Remove any characters that are not alphanumeric
     *
     * @return Sanitize
     */
    public function alnum() {
        $this->sanitized = $this->regex->replace(Regex::NOT_ALNUM);

        return $this;
    }

    /**
     * Remove any characters that are not hexadecimal
     *
     * @return Sanitize
     */
    public function hex() {
        $this->sanitized = $this->regex->replace(Regex::NOT_HEX);

        return $this;
    }

    /**
     * Remove any characters that are not numeric
     *
     * @return Sanitize
     */
    public function numeric() {
        $this->sanitized = $this->regex->replace(Regex::NOT_NUM);

        return $this;
    }

    /**
     * Remove any characters that are not a combination of letters, numbers or underscores
     *
     * @return Sanitize
     */
    public function word() {
        $this->sanitized = $this->regex->replace(Regex::NOT_WORD);

        return $this;
    }

}
