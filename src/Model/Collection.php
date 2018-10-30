<?php

namespace Envms\Osseus\Model;

use Envms\Osseus\Interfaces\Model\Collection as CollectionInterface;

/**
 * Class Collection
 *
 * Manages an array of models
 */
class Collection implements CollectionInterface
{

    protected $models = [];

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function __get(int $id)
    {
        return $this->models[$id];
    }

    /**
     * @param int $id
     */
    public function __unset(int $id)
    {
        unset($this->models[$id]);
    }

    /**
     * @param Model $model
     */
    public function add(Model $model)
    {
        $this->models[$model->id] = $model;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->models;
    }

}
