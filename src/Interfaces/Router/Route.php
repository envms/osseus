<?php

namespace Envms\Osseus\Interfaces\Router;

use Envms\Osseus\Parse\Uri;

/**
 * Interface Route
 */
interface Route
{
    /**
     * @param Uri $uri
     *
     * @return mixed
     */
    public function go(Uri $uri);

    /**
     * @param  string $controller
     * @param  string $action
     * @param  array  $params
     * @param  mixed  $options
     *
     * @return mixed
     */
    public function generate(string $controller, string $action, array $params, $options);
}
