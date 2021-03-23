<?php

namespace Envms\Osseus\Architecture;

use Envms\Osseus\Exception\InvalidException;
use Envms\Osseus\Interfaces\Architecture\Singleton as SingletonInterface;

/**
 * Singleton Class
 *
 * @see SingletonInterface for a description of what a singleton is.
 */
abstract class Singleton implements SingletonInterface
{
    protected static array $instances = [];

    /**
     * Standard class construction within Singletons is not allowed. Making this
     * a private empty method prevents construction.
     */
    private function __construct()
    {
    }

    /**
     * @param mixed $parameters - Initialization options
     *
     * @return mixed
     */
    public static function instance(...$parameters): object
    {
        $class = static::class; // late static-bound class name

        if (!isset(self::$instances[$class])) {
            $instance = new static();
            $instance->initialize($parameters);
            self::$instances[$class] = $instance;
        }

        return self::$instances[$class];
    }
    public function initialize(...$parameters): void
    {
    }

    /**
     * @note Only one instance of each subclass can ever exist. We remove the serialization
     * capability of a Singleton here.
     * @throws InvalidException
     */
    public function __sleep(): array
    {
        throw new InvalidException('Classes derived from abstract Singleton cannot be stored or serialized.');

        return [];
    }

    /**
     * @see __sleep() method's note
     *
     * @throws InvalidException
     */
    public function __wakeup()
    {
        throw new InvalidException('Classes derived from abstract Singleton cannot be retrieved or unserialized.');
    }

    /**
     * @note Similar to class construction, a Singleton instance cannot be cloned as this
     * would violate the pattern rules.
     */
    private function __clone()
    {
    }
}
