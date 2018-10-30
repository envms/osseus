<?php

namespace Envms\Osseus\Controller;

use Envms\Osseus\Interfaces\Controller\Controller;

/**
 * Class Application
 */
abstract class Application implements Controller
{
    /** @var array $params */
    protected $params = [];

    /** @var array $options */
    protected $options = [];

    /** @var array $form */
    protected $form = [];

    /**
     * @param array $params
     * @param array $options
     * @param array $form
     */
    public function __construct(array $params, array $options, array $form)
    {
        $this->params = $params;
        $this->options = $options;
        $this->form = $form;
    }
}
