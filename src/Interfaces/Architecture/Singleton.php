<?php

namespace Envms\Osseus\Interfaces\Architecture;

/**
 * Interface Singleton
 */
interface Singleton
{
    public static function instance();

    public function __sleep();

    public function __wakeup();
}
