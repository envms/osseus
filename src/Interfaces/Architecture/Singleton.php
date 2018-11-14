<?php

namespace Envms\Osseus\Interfaces\Architecture;

/**
 * Interface Singleton
 */
interface Singleton
{
    /**
     * @param mixed ...$options
     */
    public static function instance(...$options);

    public function __sleep();

    public function __wakeup();
}
