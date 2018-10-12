<?php

namespace Envms\Osseus\Database;

use Envms\Osseus\Interfaces\Database\Carbon as CarbonInterface;

/**
 * Class Carbon
 *
 * @todo Refactor class to include methods offset, limit, order and group, and not manage with all() method
 */
class Carbon implements CarbonInterface
{

    /** @var string - Matches the database module name which precedes $divider */
    protected $module = null;
    /** @var string - Defaults to underscore (_) */
    protected $divider = '_';
    /** @var string - The main table which the Carbon will be attached to */
    protected $table = null;
    /** @var string - The combined strings of $module, $divider and $table to define the data source */
    protected $source = null;

    public $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->source = $this->module . $this->divider . $this->table;

        $this->pdo = $pdo;
    }

    /**
     * @param  int    $offset
     * @param  int    $limit
     * @param  string $order
     * @param  string $group
     *
     * @return array
     */
    public function all(int $offset = 0, int $limit = 50, string $order = 'id ASC', string $group = '')
    {
        return [];
    }

}
