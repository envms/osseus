<?php

namespace Envms\Osseus\Interfaces\Architecture;

/**
 * Singleton Interface
 *
 * @description The singleton pattern allows for exactly one instance of any derived class to exist. Any
 *              instance created as a singleton will be reused, and a new instance will not be created.
 *
 * @note When using Osseus, most class instances should be created using the Instance interface. The
 *       singleton pattern is recommended to only be used in very specific use cases.
 *       An example would be a class which, without ensuring only one instance, would need be
 *       injected into nearly every class within the application.
 */
interface Singleton
{
    public static function instance(...$parameters);

    public function initialize(array $parameters): void;

    public function __sleep();

    public function __wakeup();
}
