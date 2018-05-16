<?php

namespace Envms\Osseus\Architecture;

use Envms\Osseus\Interfaces\Architecture\Singleton as SingletonInterface;

/**
 * Class Singleton
 *
 * @note When using Osseus, the singleton pattern should only be used in very specific use cases.
 * An example would be a library which, without ensuring only one instance, would need be
 * injected into nearly every class within the application.
 */

abstract class Singleton implements SingletonInterface {

    protected static $instances = [];

    /**
     * @return mixed
     */
    public static function instance() {
        $class = get_called_class(); // late static-bound class name
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static;
        }
        return self::$instances[$class];
    }

    /**
     * @note Standard class construction within Singletons is not allowed. Making this
     * a private empty method prevents construction.
     */
    private function __construct() {}

    /**
     * @note Similar to class construction, a Singleton instance cannot be cloned as this
     * would violate the pattern rules.
     */
    private function __clone() {}

    /**
     * @note Only one instance of each subclass can ever exist. We remove the serialization
     * capability of a Singleton here.
     *
     * @return bool
     */
    public function __sleep() {
        return false;
    }

    /**
     * @see __sleep() method's note
     *
     * @return bool
     */
    public function __wakeup() {
        return false;
    }

}
