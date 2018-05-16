<?php

namespace Envms\Osseus\Exception;

/**
 * Class Handler
 *
 * Used to handle uncaught exceptions. Also provides the ability to catch all exceptions and handle them gracefully,
 * depending on the environment.
 *
 * @todo Implement handling based on environment settings
 */
class Handler {

    /**
     * @param \Throwable $e
     */
    public static function get(\Throwable $e) {
        echo '<strong style="font-family:Consolas,monospace;color:#c03;">' . $e . '</strong>';
    }

}
