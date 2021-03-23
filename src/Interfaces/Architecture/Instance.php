<?php

namespace Envms\Osseus\Interfaces\Architecture;

/**
 * Instance Interface
 *
 * @description An instance is used to create new instances of classes.
 */
interface Instance
{
    /**
     * @param mixed $parameters - Passed to the new class instance constructor.
     *
     * @return object
     */
    public static function create(...$parameters): object;
}
