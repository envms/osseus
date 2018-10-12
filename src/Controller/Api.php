<?php

namespace Envms\Osseus\Controller;

use Envms\Osseus\Interfaces\Controller\Controller;
use Envms\Osseus\Database\Carbon;

/**
 * Class Api
 *
 * @todo Finish constructor and index action
 */
class Api implements Controller
{

    /** @var string $version */
    protected $version = '';
    /** @var array $params */
    protected $params = [];
    /** @var Carbon class injection */
    public $carbon;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->version = $params['version'];
        $this->params = $params['uri'];

        $reflector = new \ReflectionClass($this);
        $childClass = $reflector->getNamespaceName();

        $split = explode('-', $this->params[1]);
        $module = 0;
        $class = 'Team\\' . ucfirst($module[0]) . '\\' . ucfirst($module[1]) . '\Carbon';
    }

    public function index()
    {

    }

}
