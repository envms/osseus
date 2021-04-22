<?php

namespace Envms\Osseus\Parse;

use Envms\Osseus\Exception\InvalidException;
use Envms\Osseus\Interfaces\Parse\Parse;

/**
 * Class Json
 *
 * A very simple json parsing class
 */
class Json implements Parse
{
    /** @var null|string */
    protected $encoded;
    /** @var null|array|object */
    protected $decoded;
    /** @var array */
    protected $error = ['code' => JSON_ERROR_NONE, 'message' => 'No errors!'];

    /**
     * Assigns the referenced property $input to the parsed JSON object
     *
     * @param string $input
     * @param bool   $asArray
     * @param int    $depth
     * @param int    $options
     *
     * @return array
     *
     * @throws InvalidException
     */
    public function read(string $input, $asArray = false, $depth = 512, $options = 0)
    {
        $this->encoded = $input;
        $this->decoded = json_decode($input, $asArray, $depth, $options);

        if ($this->decoded === null) {
            $error['code'] = json_last_error();
            $error['message'] = json_last_error_msg();

            throw new InvalidException("Unable to decode provided JSON ({$this->getError()})");
        }

        return $this->decoded;
    }

    /**
     * Assigns the referenced property $data to the parsed JSON object
     *
     * @param mixed $input
     * @param int   $options
     * @param int   $depth
     *
     * @return string
     *
     * @throws InvalidException
     */
    public function write($input, $options = JSON_UNESCAPED_UNICODE, $depth = 512)
    {
        $this->decoded = $input;
        $this->encoded = json_encode($input, $options, $depth);

        if ($this->encoded === false) {
            $error['code'] = json_last_error();
            $error['message'] = json_last_error_msg();

            throw new InvalidException("Unable to encode provided input to JSON ({$this->getError()})");
        }

        return $this->encoded;
    }

    /**
     * Parses the env.json file
     *
     * @param string $path
     * @param string $file
     *
     * @throws InvalidException
     */
    public function parseEnv(string $path, string $file = 'env.json')
    {
        $this->read(file_get_contents($path . $file));
    }

    /**
     * @return string
     */
    public function getError()
    {
        return "{$this->getErrorCode()} - {$this->getErrorMessage()}";
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->error['code'];
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error['message'];
    }
}
