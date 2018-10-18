<?php

namespace Envms\Osseus\Interfaces\Exception;

/**
 * Interface Handler
 *
 * Used to handle uncaught exceptions. Also provides the ability to catch all exceptions and handle them gracefully,
 * depending on the environment.
 */
interface Handler
{

    /**
     * @param \Throwable $e
     */
    public static function get(\Throwable $e);

}
