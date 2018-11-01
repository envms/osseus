<?php

namespace Envms\Osseus\Controller;

use Envms\Osseus\Interfaces\Controller\Controller;
use Envms\Osseus\Interfaces\Model\Carbon;

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

    /** @var Carbon */
    protected $carbon;

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
