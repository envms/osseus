<?php

namespace Envms\Osseus\Interfaces\Model;

use Envms\Osseus\Model\Model;

/**
 * Interface Collection
 */
interface Collection {

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
     * @param Model $model
     */
    public function add(Model $model);

    /**
     * @return mixed
     */
    public function fetchAll();

}
