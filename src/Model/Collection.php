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

        $modelsAdded = $this->count() - $previousCount;

        return $modelsAdded;
    }

    /**
     * @param Model $model
     *
     * @return int
     */
    public function addModel(Model $model): int
    {
        $this->models[$model->id] = $model;

        return $model->id;
    }

    /**
     * @param Collection $collection
     */
    public function merge(Collection $collection)
    {
        foreach ($collection as $model) {
            $this->addModel($model);
        }
    }

    /**
     * @param string $property
     *
     * @return array
     */
    public function mapBy(string $property): array
    {
        $properties = [];
        foreach ($this->models as $model) {
            $properties[] = $model->$property;
        }

        return $properties;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return $this->getModelPropertyArray('id');
    }

    /**
     * @param string $delimiter
     *
     * @return string|array
     */
    public function getDelimitedIds(string $delimiter = ','): string
    {
        return implode($delimiter, $this->getModelIds());
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->models);
    }
}
