<?php

namespace Envms\Osseus\Router;

use Envms\Osseus\Interfaces\Router\Route as RouteInterface;
use Envms\Osseus\Exception\DoesNotExist;
use Envms\Osseus\Parse\Uri;
use Envms\Osseus\Security\Validate;

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
     *
     * @throws DoesNotExist
     *
     * @return mixed
     */
    public function go(Uri $uri, string $applicationName)
    {
        $controller = "{$applicationName}\\{$uri->getModule()}\\Controller";
        $action = $uri->getAction();

        $validate = new Validate($action);

        if ($validate->integer()) {
            $uri->addParam($action, true);
            $action = 'get';
        }

        // attempt to instantiate the proper controller and call the requested method via the Request class
        return $this->trigger($controller, $action, $uri->getParams(), $uri->getOptions());
    }

    /**
     * Instantiates the proper controller with the requested method and parameters
     *
     * @param  string       $controller
     * @param  string       $action
     * @param  array        $params
     * @param  array|string $options
     *
     * @throws DoesNotExist
     *
     * @return mixed
     */

    public function trigger(string $controller, string $action, array $params, $options)
    {
        // check if the requested controller has been already instantiated
        if (!isset($this->instance[$controller])) {
            // if not, try to instantiate the controller
            if (!class_exists($controller)) {
                throw new DoesNotExist('Controller class not found');
            }

            $this->instance[$controller] = new $controller($params, $options);
        }

        // check if controller method can be called
        if (!is_callable([$this->instance[$controller], $action])) {
            throw new DoesNotExist('Controller method not found');
        }

        // call method in requested controller and return output when applicable
        return $this->instance[$controller]->$action();
    }

}
