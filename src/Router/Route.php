<?php

namespace Envms\Osseus\Router;

use Envms\Osseus\Interfaces\Router\Route as RouteInterface;
use Envms\Osseus\Exception\{DoesNotExistException, InvalidException};
use Envms\Osseus\Parse\Uri;
use Envms\Osseus\Security\Validate;

/**
 * Class Route
 */
class Route implements RouteInterface
{
    /** @var array - Holds each controller instance */
    protected $instance = [];
    /** @var array */
    protected $map;
    /** @var string */
    protected $applicationDir;

    /**
     * Route constructor
     *
     * @param string $applicationName - The application's name and namespace
     * @param array $map
     */
    public function __construct(string $applicationName = '', array $map = [])
    {
        $this->applicationDir = "{$applicationName}\\Components\\";
        $this->map = $map;
    }

    /**
     * For determining the appropriate actions to pass to go()
     *
     * @param  Uri $uri
     *
     * @throws DoesNotExistException|InvalidException
     *
     * @return mixed
     */
    public function go(Uri $uri)
    {
        $controller = $this->determineController($uri->getComponent());
        $action = $this->determineAction($uri->getAction(), $uri);
        $params = $this->mapParams($uri->getComponent(), $action, $uri->getParams());

        return $this->generate($controller, $action, $params, $uri->getOptions());
    }

    /**
     * Instantiates the proper controller and attempts to call the requested
     * method with parameters and options
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

    /**
     * @param $component
     *
     * @return string
     */
    public function determineController($component)
    {
        if (empty($component)) {
            return "{$this->applicationDir}Index\\Controller";
        }

        return "{$this->applicationDir}{$component}\\Controller";
    }

    /**
     * @param string $action
     * @param Uri $uri
     *
     * @return string
     */
    public function determineAction($action, Uri $uri)
    {
        if (empty($action)) {
            return 'index';
        }

        $validateAction = new Validate($action);

        if ($validateAction->integer()) {
            $uri->prependParam($action);
            return 'get';
        }

        return $action;
    }

    /**
     * @param string $controller
     * @param string $action
     * @param array $params
     *
     * @throws InvalidException
     *
     * @return array
     */
    public function mapParams(string $controller, string $action, array $params): array
    {
        if (isset($this->map[$controller][$action])) {
            $map = $this->map[$controller][$action];
            if (count($map) === count($params)) {
                return array_combine($map, $params);
            }

            throw new InvalidException('URL and Route parameter count must be equal');
        }

        return $params;
    }
}
