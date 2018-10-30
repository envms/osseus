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
    protected $error = ['code' => JSON_ERROR_NONE, 'message' => 'No error'];

    /** @var null|string */
    public $rawData = null;
    /** @var null|array|object */
    public $parsedData = null;

    /**
     * Assigns the referenced property $input to the parsed JSON object
     *
     * @param string $input
     * @param array  $flags
     *
     * @throws Invalid
     *
     * @return array
     */
    public function read($input, $flags = ['toArray' => false])
    {
        $this->rawData = $input;
        $this->parsedData = json_decode($input, $flags['toArray']);

        if ($this->parsedData === null) {
            $error['code'] = json_last_error();
            $error['message'] = json_last_error_msg();

            throw new Invalid('Unable to decode JSON string. Invalid JSON');
        }

        return $this->parsedData;
    }

    /**
     * Assigns the referenced property $data to the parsed JSON object
     *
     * @param mixed $input
     * @param array $flags
     *
     * @throws Invalid
     *
     * @return string
     */
    public function write($input, $flags = ['options' => JSON_UNESCAPED_UNICODE, 'depth' => 512])
    {
        $this->parsedData = $input;
        $this->rawData = json_encode($input, $flags['options'], $flags['depth']);

        if ($this->rawData === false) {
            $error['code'] = json_last_error();
            $error['message'] = json_last_error_msg();

            throw new Invalid('Unable to encode input to string');
        }

        return $this->rawData;
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
