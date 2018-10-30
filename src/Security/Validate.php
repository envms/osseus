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
    public function __construct($data)
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
        $this->data = $data;
        $this->regex->reset($this->data);

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
     * @note This will NOT validate non-ASCII URIs
     *
     * @return bool
     */
    public function url()
    {
        return filter_var($this->data, FILTER_VALIDATE_URL);
    }

    /**
     * @return bool
     */
    public function integer()
    {
        return ctype_digit((string)$this->data);
    }

    /**
     * @return $this
     */
    public function float()
    {
        return is_float($this->data + 0);
    }

    /**
     * Uses regex to support utf-8
     *
     * @return bool|int
     */
    public function alpha()
    {
        return $this->regex->match(Regex::NOT_ALPHA);
    }

    /**
     * Uses regex to support utf-8
     *
     * @return bool|int
     */
    public function alphanumeric()
    {
        return $this->regex->match(Regex::NOT_ALNUM);
    }

    /**
     * @return bool
     */
    public function hex()
    {
        return ctype_xdigit((string)$this->data);
    }

    /**
     * @return bool
     */
    public function numeric()
    {
        return is_numeric($this->data);
    }

    /**
     * A word is any combination of letters, numbers or underscores
     *
     * @return bool|int
     */
    public function word()
    {
        return $this->regex->match(Regex::NOT_WORD);
    }
}
