<?php

namespace Envms\Osseus\Utils;

/**
 * Class Regex
 */
class Regex
{
    /** Regex constants make use of POSIX character classes */
    const ALNUM = '[[:alnum:]]';
    const ALPHA = '[[:alpha:]]';
    const ASCII = '[[:ascii:]]';
    const CONTROL = '[[:cntrl:]]';
    const HEX = '[[:xdigit:]]';
    const NUM = '[[:digit:]]';
    const PRINTABLE = '[[:print:]]';
    const PUNCTUATION = '[[:punct:]]';
    const VISIBLE = '[[:graph:]]';
    const WHITESPACE = '[[:space:]]';
    const WORD = '[[:word:]]';

    const NOT_ALNUM = '[[:^alnum:]]';
    const NOT_ALPHA = '[[:^alpha:]]';
    const NOT_ASCII = '[[:^ascii:]]';
    const NOT_CONTROL = '[[:^cntrl:]]';
    const NOT_HEX = '[[:^xdigit:]]';
    const NOT_NUM = '[[:^digit:]]';
    const NOT_PRINTABLE = '[[:^print:]]';
    const NOT_PUNCTUATION = '[[:^punct:]]';
    const NOT_VISIBLE = '[[:^graph:]]';
    const NOT_WHITESPACE = '[[:^space:]]';
    const NOT_WORD = '[[:^word:]]';

    /** @var mixed */
    protected $subject;
    /** @var mixed */
    public $store;

    /**
     * @param $subject
     */
    public function __construct($subject)
    {
        $this->reset($subject);
    }

    /**
     * @param $subject
     */
    public function reset($subject)
    {
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
    public function match(string $pattern, string $modifiers = 'u', int $flags = 0, int $offset = 0)
    {
        return preg_match("/{$pattern}/{$modifiers}", $this->subject, $this->store, $flags, $offset);
    }

    /**
     * @param  string $pattern
     * @param  string $modifiers
     * @param  int    $flags
     * @param  int    $offset
     *
     * @return int|bool
     */
    public function matchAll(string $pattern, string $modifiers = 'u', int $flags = PREG_PATTERN_ORDER, int $offset = 0)
    {
        return preg_match_all("/{$pattern}/{$modifiers}", $this->subject, $this->store, $flags, $offset);
    }

    /**
     * @param  string $pattern
     * @param  string $modifiers
     * @param  string $replace
     * @param  int    $limit
     *
     * @return string|bool
     */
    public function replace(string $pattern, string $replace = '', string $modifiers = 'u', int $limit = -1)
    {
        return preg_replace("/{$pattern}/{$modifiers}", $replace, $this->subject, $limit, $this->store);
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
