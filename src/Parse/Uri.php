<?php

namespace Envms\Osseus\Parse;

use Envms\Osseus\Interfaces\Parse\Parse;

/**
 * Class Uri
 *
 * Full URI parsing system. Can be passed to Route class
 */
class Uri implements Parse
{

    /** @var array|bool */
    protected $uri;
    /** @var array - populated at Uri construction time */
    protected $route = [];
    /** @var array - contains API params */
    protected $api = [];
    /** @var string */
    protected $component = '';
    /** @var string */
    protected $action = '';
    /** @var array */
    protected $params = [];
    /** @var array|string */
    protected $options = null;

    /**
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = parse_url($uri);

        if ($this->uri !== false) {
            $this->route = explode('/', trim($this->uri['path'], '/'));

            $isApi = strpos($this->route[0], 'api') === 0;

            if ($isApi) {
                array_shift($this->route);
                $this->api['version'] = array_shift($this->route);
            }

            $route = $this->route;

            $this->component = str_replace('-', '', ucwords(array_shift($route), '-'));
            $this->action = lcfirst(str_replace('-', '', ucwords(array_shift($route), '-')));
            $this->params = $route;

            unset($route);

            $this->setOptions();
        }
    }

    /**
     * @param $uri
     * @param $flags
     *
     * @return mixed
     */
    public function read($uri, $flags): array
    {
        return parse_url(urldecode($uri));
    }

    /**
     * @param $uri
     * @param $flags
     *
     * @return string
     */
    public function write($uri, $flags): string
    {
        $scheme = (!isset($uri['scheme'])) ? $uri['scheme'] . ':' : '';
        $host = (!isset($uri['host'])) ? '//' . $uri['host'] : '';
        $port = (!isset($uri['port'])) ? ':' . $uri['port'] : '';
        $path = (!isset($uri['path'])) ? $uri['path'] : '';
        $query = (!isset($uri['query'])) ? '?' . $uri['query'] : '';
        $fragment = (!isset($uri['fragment'])) ? '#' . $uri['fragment'] : '';

        $user = isset($uri['user']) ? $uri['user'] : '';
        $pass = isset($uri['pass']) ? ':' . $uri['pass']  : '';
        $pass = ($user || $pass) ? "$pass@" : '';

        return "{$scheme}{$user}{$pass}{$host}{$port}{$path}{$query}{$fragment}";
    }

    /**
     * @return array|bool
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return array|bool
     */
    public function getHost()
    {
        return $this->uri['host'];
    }

    /**
     * @return array|bool
     */
    public function getPath()
    {
        return $this->uri['path'];
    }

    /**
     * @return array|bool
     */
    public function getQuery()
    {
        return $this->uri['query'];
    }

    /**
     * @return array
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->api['version'];
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->api['namespace'];
    }

    /**
     * @return string
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return array|string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $key
     *
     * @return array|string
     */
    public function getOption($key)
    {
        return (is_array($this->options)) ? $this->options[$key] : $this->options;
    }

    /**
     * @return bool
     */
    public function isApiCall()
    {
        return isset($this->api['version']);
    }

    /**
     * @param      $value
     * @param bool $prepend
     */
    public function addParam($value, bool $prepend = false)
    {
        ($prepend) ? array_unshift($this->params, $value) : array_push($this->params, $value);
    }

    /**
     * A complete query string parser
     */
    protected function setOptions()
    {
        if (!empty($this->uri['query'])) {
            if (strpos($this->uri['query'], '&') !== false) { // check for multiple options, split by &
                $options = explode('&', $this->uri['query']);

                foreach ($options as $option) {
                    if (strpos($option, '=') !== false) { // check for a key-value pair, split by =
                        $keyValues = explode('=', $option);
                        $this->options[$keyValues[0]] = $keyValues[1];
                    } else { // otherwise set the key to the value
                        $this->options[$option] = $option;
                    }
                }
            } else { // if there are no multiple options, check for a key-value pair, split by =
                if (strpos($this->uri['query'], '=') !== false) {
                    $keyValues = explode('=', $this->uri['query']);
                    $this->options[$keyValues[0]] = $keyValues[1];
                } else { // otherwise query is just a flat string
                    $this->options[$this->uri['query']] = $this->uri['query'];
                }
            }
        }
    }

}
