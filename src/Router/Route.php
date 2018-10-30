<?php

namespace Envms\Osseus\Router;

use Envms\Osseus\Interfaces\Router\Route as RouteInterface;
use Envms\Osseus\Exception\{DoesNotExistException, InvalidException};
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
    /** @var array */
    protected $map;
    /** @var string */
    protected $applicationName;

    /**
     * Route constructor
     *
     * @param string $applicationName - The calling application's namespace
     * @param array $map
     */
    public function __construct(string $applicationName = '', array $map = [])
    {
        $this->applicationName = "{$applicationName}\\";
        $this->map = $map;
    }

    /**
     * For determining the appropriate actions to pass to go()
     *
     * @param  Uri    $uri
     *
     * @throws DoesNotExistException|InvalidException
     *
     * @return mixed
     */
    public function go(Uri $uri)
    {
        $controller = "{$applicationName}\\{$uri->getModule()}\\Controller";
        $action = $uri->getAction();

        $validate = new Validate($action);

        if ($validate->integer()) {
            $uri->addParam($action, true);
            $action = 'get';
        }

        // attempt to instantiate the proper controller and call the requested method via the Request class
        return $this->generate($controller, $action, $uri->getParams(), $uri->getOptions());
    }

    /**
     * Instantiates the proper controller with the requested method and parameters
     *
     * @param  string       $controller
     * @param  string       $action
     * @param  array        $params
     * @param  array|string $options
     *
     * @throws DoesNotExistException
     *
     * @return mixed
     */

    public function generate(string $controller, string $action, array $params, $options)
    {
        // check if the requested controller has been already instantiated
        if (!isset($this->instance[$controller])) {
            // if not, try to instantiate the controller
            if (!class_exists($controller)) {
                throw new DoesNotExistException('Controller class not found');
            }

            $this->instance[$controller] = new $controller($params, $options, $_POST);
        }

        // check if controller method can be called
        if (!is_callable([$this->instance[$controller], $action])) {
            throw new DoesNotExistException('Controller method not found');
        }

        // call method in requested controller and return output when applicable
        return $this->instance[$controller]->$action();
    }

}
