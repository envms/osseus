<?php

namespace Envms\Osseus\Exception;

use Envms\Osseus\Dev\Debug;

/**
 * Class Exception
 *
 * @description
 */
abstract class Exception extends \Exception
{
    /** @var string */
    private $string;
    /** @var string */
    private $trace;

    /** @var string */
    protected $message = 'Default \Envms\Osseus\Exception';

    /**
     * Exception()
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
    {
        if ($message === null) {
            throw new $this(get_class($this));
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . ": {$this->message} ({$this->code}) in {$this->file} line {$this->line}" . Debug::LINEBREAK . "{$this->getTraceAsString()}";
    }

}
