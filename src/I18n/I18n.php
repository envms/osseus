<?php

namespace Envms\Osseus\I18n;

/**
 * Class I18n
 *
 * Manages internationalization of static and complex string arrangements within the application
 *
 * @todo Implement SplFixedArray to improve performance and memory usage
 */
class I18n
{

    protected $phrases = [];
    /** @var array  - Static sentences and phrases */
    protected $complex = [];

    /** @var array  - Complex and variable sentences which require parsing */

    public function __construct()
    {

    }

    /**
     * @param  array $data
     * @param  bool  $preserve - If true, the arrays will merge with the current data, overwriting when required.
     *
     * @return bool
     */
    public function add(array $data, $preserve = false)
    {
        if (!is_array($data['phrases']) || !is_array($data['complex'])) {
            return false;
        }

        $this->phrases = (!$preserve) ? array_merge($this->phrases, $data['phrases']) : array_merge($data['phrases'], $this->phrases);
        $this->complex = (!$preserve) ? array_merge($this->complex, $data['complex']) : array_merge($data['complex'], $this->complex);

        return true;
    }

    /**
     * @param  string $key
     *
     * @return null|string
     */
    public function __get(string $key)
    {
        return (isset($this->phrases[$key])) ? $this->phrases[$key] : null;
    }

    /**
     * @param  string $key
     * @param  mixed  $inserts
     *
     * @return null|string
     */
    public function get(string $key, ...$inserts)
    {
        if (!array_key_exists($key, $this->complex)) {
            return null;
        }

        $string = $this->complex[$key];

        foreach ($inserts as $id => $replace) {
            $string = str_replace('#/' . ++$id . '\#', $replace, $string);
        }

        return $string;
    }

}
