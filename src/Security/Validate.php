<?php

namespace Envms\Osseus\Security;

use Envms\Osseus\Utils\Regex;

/**
 * Class Validate
 *
 * Any user submitted data that needs validation should be passed through
 * the necessary methods within the Validate class. This class differs from Sanitize by only
 * providing errors to what is invalid, and not automatically cleaning dirty data.
 */
class Validate
{
    /** @var array */
    protected $errors = [];
    /** @var mixed */
    protected $data;
    /** @var Regex */
    protected $regex;

    /**
     * @param $data
     */
    public function __construct($data = '')
    {
        $this->data = $data;
        $this->regex = new Regex($this->data);
    }

    /**
     * Set internal data to a new value.
     *
     * @param  mixed $data
     *
     * @return Validate $this
     */
    public function reset($data)
    {
        if ($data !== null) {
            $this->data = $data;
            $this->regex->reset($this->data);
        }

        return $this;
    }

    /**
     * Get the current data being validated
     *
     * @return mixed
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * @param  bool $asString
     *
     * @return array|string
     */
    public function getErrors(bool $asString = false)
    {
        if ($asString) {
            $errors = '';

            foreach ($this->errors as $error) {
                $errors .= "{$error} ";
            }

            return $errors;
        }

        return $this->errors;
    }

    /**
     * @param mixed $data
     *
     * @note This will NOT validate non-ASCII URIs
     *
     * @return bool
     */
    public function url($data = null)
    {
        $this->reset($data);
        return filter_var($this->data, FILTER_VALIDATE_URL);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function integer($data = null)
    {
        $this->reset($data);
        return ctype_digit((string)$this->data);
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function float($data = null)
    {
        $this->reset($data);
        return is_float($this->data + 0);
    }

    /**
     * Uses regex to support utf-8
     *
     * @param mixed $data
     *
     * @return bool|int
     */
    public function alpha($data = null)
    {
        $this->reset($data);
        return $this->regex->match(Regex::NOT_ALPHA);
    }

    /**
     * Uses regex to support utf-8
     *
     * @param mixed $data
     *
     * @return bool|int
     */
    public function alphanumeric($data = null)
    {
        $this->reset($data);
        return $this->regex->match(Regex::NOT_ALNUM);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function hex($data = null)
    {
        $this->reset($data);
        return ctype_xdigit((string)$this->data);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function numeric($data = null)
    {
        $this->reset($data);
        return is_numeric($this->data);
    }

    /**
     * A word is any combination of letters, numbers or underscores
     *
     * @param mixed $data
     *
     * @return bool|int
     */
    public function word($data = null)
    {
        $this->reset($data);
        return $this->regex->match(Regex::NOT_WORD);
    }

    /**
     * @param int   $minLength
     * @param int   $maxLength
     * @param mixed $data
     *
     * @return bool
     */
    public function length(int $minLength, int $maxLength, $data = null)
    {
        $this->reset($data);

        if (is_array($this->data) || is_object($this->data)) {
            $length = count($this->data);
        } else {
            $length = strlen((string)$this->data);
        }

        return ($length > $minLength) && ($length < $maxLength);
    }
}
