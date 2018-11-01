<?php

namespace Envms\Osseus\Model;

use Envms\Osseus\Interfaces\Model\Collection as CollectionInterface;

/**
 * Class Collection
 *
 * Manages an array of models
 */
abstract class Collection implements CollectionInterface
{
    /** @var int */
    private $position;

    /** @var array */
    protected $models = [];

    public function __construct()
    {
        $this->position = 0;
    }

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

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->models[$this->position];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return mixed|void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return mixed|void
     */
    public function previous()
    {
        --$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->models[$this->position]);
    }
}
