<?php

namespace Envms\Osseus\I18n;

/**
 * Class I18n
 *
 * Manages internationalization of static and complex string arrangements within the application
 */
class I18n
{
    /** @var array  - Static sentences and phrases */
    protected $phrases = [];
    /** @var array  - Complex and variable sentences which require parsing */
    protected $complex = [];

    /**
     * I18n constructor
     *
     * @param array $phrases
     * @param array $complex
     */
    public function __construct(array $phrases, array $complex)
    {
        $this->addPhrases($phrases);
        $this->addComplex($complex);
    }

    /**
     * @param  array $data
     * @param  bool  $preserve - If true, the arrays will merge with the current data, overwriting keys when required.
     *
     * @return bool
     */
    public function addPhrases(array $data, $preserve = false): bool
    {
        if (!is_array($data)) {
            return false;
        }

        $this->phrases = (!$preserve) ? array_merge($this->phrases, $data) : array_merge($data, $this->phrases);

        return true;
    }

    /**
     * @param  array $data
     * @param  bool  $preserve - If true, the arrays will merge with the current data, overwriting keys when required.
     *
     * @return bool
     */
    public function addComplex(array $data, $preserve = false): bool
    {
        if (!is_array($data)) {
            return false;
        }

        $this->complex = (!$preserve) ? array_merge($this->complex, $data) : array_merge($data, $this->complex);

        return true;
    }

    /**
     * @param string $key
     *
     * @return null|string
     */
    public function __get(string $key): ?string
    {
        return (isset($this->phrases[$key])) ? $this->phrases[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed  $inserts
     *
     * @return null|string
     */
    public function get(string $key, ...$inserts): ?string
    {
        if (!array_key_exists($key, $this->complex)) {
            return null;
        }

        return vsprintf($this->complex[$key], $inserts);
    }
}
