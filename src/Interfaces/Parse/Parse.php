<?php

namespace Envms\Osseus\Interfaces\Parse;

/**
 * Interface Controller
 */
interface Parse
{
    /**
     * @param $input
     * @param $flags
     */
    public function read(string $input, $flags);

    /**
     * @param $input
     * @param $flags
     */
    public function write($input, $flags);
}
