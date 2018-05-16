<?php

namespace Envms\Osseus\Utils;

/**
 * Class Regex
 *
 *
 */
class Regex {

    /** Regex constants make use of POSIX character classes */
    const ALNUM       = '[[:alnum:]]';
    const ALPHA       = '[[:alpha:]]';
    const ASCII       = '[[:ascii:]]';
    const CONTROL     = '[[:cntrl:]]';
    const HEX         = '[[:xdigit:]]';
    const NUM         = '[[:digit:]]';
    const PRINTABLE   = '[[:print:]]';
    const PUNCTUATION = '[[:punct:]]';
    const VISIBLE     = '[[:graph:]]';
    const WHITESPACE  = '[[:space:]]';
    const WORD        = '[[:word:]]';

    const NOT_ALNUM       = '[[:^alnum:]]';
    const NOT_ALPHA       = '[[:^alpha:]]';
    const NOT_ASCII       = '[[:^ascii:]]';
    const NOT_CONTROL     = '[[:^cntrl:]]';
    const NOT_HEX         = '[[:^xdigit:]]';
    const NOT_NUM         = '[[:^digit:]]';
    const NOT_PRINTABLE   = '[[:^print:]]';
    const NOT_PUNCTUATION = '[[:^punct:]]';
    const NOT_VISIBLE     = '[[:^graph:]]';
    const NOT_WHITESPACE  = '[[:^space:]]';
    const NOT_WORD        = '[[:^word:]]';

    const SPLIT_EACH     = 0;
    const SPLIT_SECTIONS = 1;
    const SPLIT_LENGTH   = 2;

    /** @var mixed */
    protected $subject;
    /** @var mixed */
    public $store;

    /**
     * @param $subject
     */
    public function __construct($subject) {
        $this->reset($subject);
    }

    /**
     * @param $subject
     */
    public function reset($subject) {
        $this->subject = $subject;
    }

    /**
     * @param  string $pattern
     * @param  string $modifiers
     * @param  int    $flags
     * @param  int    $offset
     *
     * @return int|bool
     */
    public function match($pattern, $modifiers = 'u', $flags = 0, $offset = 0) {
        return preg_match('/' . $pattern . '/' . $modifiers, $this->subject, $this->store, $flags, $offset);
    }

    /**
     * @param  string $pattern
     * @param  string $modifiers
     * @param  int    $flags
     * @param  int    $offset
     *
     * @return int|bool
     */
    public function matchAll($pattern, $modifiers = 'u', $flags = PREG_PATTERN_ORDER, $offset = 0) {
        return preg_match('/' . $pattern . '/' . $modifiers, $this->subject, $this->store, $flags, $offset);
    }

    /**
     * @param  string $pattern
     * @param  string $modifiers
     * @param  string $replace
     * @param  int    $limit
     *
     * @return string|bool
     */
    public function replace($pattern, $replace = '', $modifiers = 'u', $limit = -1) {
        return preg_replace('/' . $pattern . '/' . $modifiers, $replace, $this->subject, $limit, $this->store);
    }

    /**
     * A unicode-safe method of splitting a string
     *
     * @param string $string
     * @param int    $type  - How many parts to split the string into
     * @param int    $count - How long each section should be (if the $sections argument is not set)
     *
     * @return array
     */
    public function split($string, $type = self::SPLIT_EACH, $count = 1) {
        $strlen = mb_strlen($string);

        // if only a string is passed, split the string character by character
        if ($type === self::SPLIT_EACH) {
            return preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);
        }
        else {
            $split = [];

            // if the sections argument is set, overwrite the length argument with an equal split value
            if ($type === self::SPLIT_SECTIONS) {
                $length = ceil($strlen / $count);

                for ($i = 0; $i < $count; $i++) {
                    $start = $i * $length;
                    $split[] = mb_substr($string, $start, $length);
                }

                return $split;
            }

            // otherwise use the requested section
            for ($i = 0; $i < $strlen; $i += $count) {
                $split[] = mb_substr($string, $i, $count);
            }

            return $split;
        }
    }

}
