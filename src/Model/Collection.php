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
    /** @var array */
    protected $models = [];
    /** @var string */
    protected $modelName;

    /**
     * Collection constructor
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->modelName = $this->getModelName();
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
     * @return array
     */
    public function fetchAll()
    {
        return $this->models;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->models);
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    public function getModelName()
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getNamespaceName() . '\Model';
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function add(array $data): int
    {
        $id = (isset($data['id'])) ? $data['id'] : $this->count();
        $this->models[$id] = new $this->modelName($data);

        return $id;
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function addMany(array $data): int
    {
        $previousCount = $this->count();

        foreach ($data as $modelData) {
            $this->add($modelData);
        }

        $modelsAdded = $previousCount - $this->count();

        return $modelsAdded;
    }
}
