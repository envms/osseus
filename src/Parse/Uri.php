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
     * @param bool   $isApi
     */
    public function __construct(string $uri, bool $isApi)
    {
        $this->uri = parse_url($uri);

        if ($this->uri !== false) {
            $route = $this->api['route'] = explode('/', $this->uri['path']);
            unset($route[0]); // before first slash is always empty

            if ($isApi === true) {
                $this->api['version'] = $route[1];
                unset($route[1]);

                $this->api['namespace'] = implode('\\', array_map('ucfirst', explode('-', $route[2])));
                unset($route[2]);

                $this->setParams($route);
                $this->setOptions();
            } else { // if this isn't an api call, use the standard application routes
                $this->controller = ucfirst($route[1]);
                $this->action = $route[2];
                $this->setParams($route);
                $this->setOptions();
            }
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
