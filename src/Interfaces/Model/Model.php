<?php

namespace Envms\Osseus\Interfaces\Model;

/**
 * Interface Model
 */
interface Model
{
    /**
     * @param  string $key
     *
     * @return bool
     */
    public function __isset(string $key);

    /**
     * @param  string $key
     * @param  mixed  $value
     */
    public function __set(string $key, $value);

    /**
     * @param  string $key
     */
    public function __unset(string $key);

    /**
     * @param  string $key
     *
     * @return mixed
     */
    public function __get(string $key);
}
