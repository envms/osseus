<?php

namespace Envms\Osseus\Interfaces\Architecture;

/**
 * Interface Singleton
 */
interface Singleton
{

    static function instance();

    function __sleep();

    function __wakeup();

}
