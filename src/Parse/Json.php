<?php

namespace Envms\Osseus\Parse;

use Envms\Osseus\Interfaces\Parse\Parse;
use Envms\Osseus\Exception\Invalid;

/**
 * Class Json
 *
 * A very simple input parsing class
 */
class Json implements Parse
{

    /** @var array */
    protected $error;

    /** @var mixed */
    public $data;

    /**
     * Json constructor
     */
    public function __construct()
    {
        $this->data = null;
        $this->error = ['code' => JSON_ERROR_NONE, 'message' => 'No error'];
    }

    /**
     * Assigns the referenced property $input to the parsed JSON object
     *
     * @param string $input
     * @param array  $options
     *
     * @throws Invalid
     */
    public function read($input, $options = ['toArray' => false])
    {
        $this->data = json_decode($input, $options['toArray']);

        if ($this->data === null) {
            $error['code'] = json_last_error();
            $error['message'] = json_last_error_msg();

            throw new Invalid('Unable to decode JSON string. Invalid JSON');
        }
    }

    /**
     * Assigns the referenced property $data to the parsed JSON object
     *
     * @param mixed $input
     * @param array $options
     *
     * @throws Invalid
     *
     * @return string
     */
    public function write($input, $options = ['options' => JSON_UNESCAPED_UNICODE, 'depth' => 512])
    {
        $this->data = json_encode($input, $options['options'], $options['depth']);

        if ($this->data === false) {
            $error['code'] = json_last_error();
            $error['message'] = json_last_error_msg();

            throw new Invalid('Unable to encode input to string');
        }

        return $this->data;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Parses the env.json file
     *
     * @param string $path
     * @param string $file
     *
     * @throws Invalid
     */
    public function parseEnv(string $path, string $file = 'env.json')
    {
        $this->read(file_get_contents($path . $file));
    }

}
