<?php

namespace Envms\Osseus\Architecture;

use Envms\Osseus\Interfaces\Architecture\Singleton as SingletonInterface;

/**
 * Class Singleton
 *
 * @note When using Osseus, the singleton pattern is recommended to only be used in very specific use cases.
 *       An example would be a class which, without ensuring only one instance, would need be
 *       injected into nearly every class within the application.
 */
abstract class Singleton implements SingletonInterface
{
    /** @var array */
    protected static $instances = [];

    /**
     * @param mixed $parameters - Initialization options
     *
     * @return mixed
     */
    public static function instance(...$parameters)
    {
        $class = get_called_class(); // late static-bound class name

        if (!isset(self::$instances[$class])) {
            $instance = new static();
            $instance->initialize($parameters);
            self::$instances[$class] = $instance;
        }

        return self::$instances[$class];
    }

    /**
     * @note Provides an optional hook, which is called within instance()
     *
     * @param array $parameters
     */
    protected function initialize(array $parameters = []): void
    {
    }

    /**
     * @note Standard class construction within Singletons is not allowed. Making this
     * a private empty method prevents construction.
     */
    private function __construct()
    {
    }

    /**
     * @note Similar to class construction, a Singleton instance cannot be cloned as this
     * would violate the pattern rules.
     */
    private function __clone()
    {
    }

    /**
     * @note Only one instance of each subclass can ever exist. We remove the serialization
     * capability of a Singleton here.
     *
     * @return bool
     */
    public function __sleep()
    {
        return false;
    }

    /**
     * @see __sleep() method's note
     *
     * @return bool
     */
    public function __wakeup()
    {
        return false;
    }
}
