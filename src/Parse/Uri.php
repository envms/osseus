<?php

namespace Envms\Osseus\Parse;

/**
 * Class Uri
 *
 * Full URI parsing to pass to Router
 *
 * @todo Implement Parse interface
 * @todo Make parser flexible, capable of handling non-uniform URIs
 * @todo split API/non-API logic
 */
class Uri
{

    /** @var array|bool */
    protected $uri;
    /** @var array - populated at Uri construction time */
    protected $route = [];
    /** @var array - contains API params */
    protected $api = [];
    /** @var string */
    protected $module = '';
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

            $this->module = str_replace('-', '', ucwords(array_shift($route), '-'));
            $this->action = lcfirst(str_replace('-', '', ucwords(array_shift($route), '-')));
            $this->params = $route;

            unset($route);

            $this->setOptions();
        }
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
    public function getModule()
    {
        return $this->module;
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
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = (!empty($params[3])) ? array_values($params) : null;
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
                        $kv = explode('=', $option);
                        $this->options[$kv[0]] = $kv[1];
                    } else { // otherwise set the key to the value
                        $this->options[$option] = $option;
                    }
                }
            } else { // if there are no multiple options, check for a key-value pair, split by =
                if (strpos($this->uri['query'], '=') !== false) {
                    $kv = explode('=', $this->uri['query']);
                    $this->options[$kv[0]] = $kv[1];
                } else { // otherwise query is just a flat string
                    $this->options = $this->uri['query'];
                }
            }
        }
    }

}
