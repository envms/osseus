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
    public function __get(int $id);

    /**
     * @param int $id
     */
    public function __unset(int $id);

    /**
     * @param array $data
     */
    public function add(array $data);

    /**
     * @return mixed
     */
    public function fetchAll();

}
