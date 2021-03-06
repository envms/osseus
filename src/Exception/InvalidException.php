<?php

namespace Envms\Osseus\Exception;

/**
 * Class Invalid
 *
 * Base exception class for "invalid" exceptions. Can be extended to include exceptions
 * to invalid argument, data type etc.
 */
class InvalidException extends Exception
{
    protected $message = 'Invalid exception thrown';
}
