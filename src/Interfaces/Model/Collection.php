<?php

namespace Envms\Osseus\Interfaces\Model;

use Envms\Osseus\Model\Model;

/**
 * Interface Collection
 */
interface Collection extends \Iterator
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
