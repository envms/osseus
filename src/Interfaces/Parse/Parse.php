<?php

namespace Envms\Osseus\Interfaces\Parse;

/**
 * Interface Controller
 */
interface Parse
{

    /**
     * @param $input
     * @param $options
     */
    public function read($input, $options);

    /**
     * @param $input
     * @param $options
     */
    public function write($input, $options);

}
