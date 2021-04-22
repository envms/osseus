<?php

namespace Envms\Osseus\Interfaces\Model;

/**
 * Interface Collection
 */
interface Collection extends \IteratorAggregate
{

    /**
     * @param  int $id
     *
     * @return mixed
     */
    public function __get(string $id);

    /**
     * @param int $id
     */
    public function __unset(string $id);

    /**
     * @param array $data
     */
    public function add(array $data);

    /**
     * @return mixed
     */
    public function all();

}
