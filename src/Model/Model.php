<?php

namespace Envms\Osseus\Model;

use Envms\Osseus\Interfaces\Model\Model as ModelInterface;

/**
 * Class Model
 *
 * A standard model structure for data storage
 */
class Model implements ModelInterface
{

    /** @var int */
    public $id = null;
    /** @var array */
    protected $data = [];

    /**
     * Model()
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['id'])) {
            $this->id = (int)$data['id']; // typecasting here prevents "valid" string integers like 012, converting it to 12 instead
            unset($data['id']);
            $this->data = $data;
        }
    }

    /**
     * __isset() - Magic Method
     *
     * @param  string $key
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return isset($this->data[$key]);
    }

    /**
     * __set() - Magic Method
     *
     * @param  string $key
     * @param  mixed  $value
     */
    public function __set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * __unset() - Magic Method
     *
     * @param  string $key
     */
    public function __unset(string $key)
    {
        unset($this->data[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : false;
    }

}
