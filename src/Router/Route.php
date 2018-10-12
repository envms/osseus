<?php

namespace Envms\Osseus\Router;

use Envms\Osseus\Interfaces\Router\Route as RouteInterface;
use Envms\Osseus\Parse\Uri;
use Envms\Osseus\Exception\NotFound;

/**
 * Class Route
 *
 *
 */
class Route implements RouteInterface
{

    /** @var array - Holds each controller instance */
    protected $instance = [];

    /**
     * Route constructor
     */
    public function __construct()
    {

    }

    /**
     * For determining the appropriate actions to pass to go()
     *
     * @param  Uri    $uri
     * @param  string $applicationName - The base applicationName's namespace
     * @param  bool   $isApi
     *
     * @return mixed
     *
     * @todo Implement options functionality
     */
    public function go(Uri $uri, string $applicationName, bool $isApi)
    {
        $action = 'index';
        $params = [];
        $options = null;

        if ($isApi === true) {
            $controller = $applicationName . '\Api\\' . $uri->getNamespace() . '\Controller';

            if ($uri->getParams() !== null) {
                $action = 'select';
            }

            return $this->trigger($controller, $action, $uri->getParams(), $uri->getOptions());
        } else {
            $controller = $applicationName . '\\' . $uri->getController() . '\Controller';
            $action = $uri->getAction();

            if (!empty($url[2])) {
                $key = '';
                $isKey = true;

                // iterate through each parameter given to assign key value pairs
                foreach ($url as $u) {
                    if ($isKey) {
                        $key = $u;
                        $isKey = false;
                    } else {
                        $params[$key] = $u;
                        $isKey = true;
                    }
                }
            }

            // attempt to instantiate the proper controller and call the requested method via the Request class
            return $this->trigger($controller, $action, $params, $options);
        }
    }

    /**
     * Instantiates the proper controller with the requested method and parameters
     *
     * @param  string       $controller
     * @param  string       $action
     * @param  array        $params
     * @param  array|string $options
     *
     * @throws NotFound
     *
     * @return mixed
     */

    public function trigger(string $controller, string $action, array $params, $options)
    {
        // check if the requested controller has been already instantiated
        if (!isset($this->instance[$controller])) {
            // if not, try to instantiate the controller
            if (!class_exists($controller)) {
                throw new NotFound('Controller class not found');
            }

            $this->instance[$controller] = new $controller($params, $options);
        }

        // check if controller method can be called
        if (!is_callable([$this->instance[$controller], $action])) {
            throw new NotFound('Controller method not found');
        }

        // call method in requested controller and return output when applicable
        return $this->instance[$controller]->$action();
    }

}
