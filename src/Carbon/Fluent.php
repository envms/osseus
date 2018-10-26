<?php

namespace Envms\Osseus\Carbon;

use Envms\FluentPDO\{Exception, Query};
use Envms\Osseus\Database\Table;
use Envms\Osseus\Interfaces\Database\Carbon as CarbonInterface;

/**
 * Class Fluent
 */
class Fluent implements CarbonInterface
{
    /** @var Table - The combined strings of $module, $divider and $table to define the data source */
    protected $table = null;

    /** @var \PDO */
    public $fluent;

    /**
     * @param Query $fluent
     * @param Table $table
     */
    public function __construct(Query $fluent, Table $table)
    {
        $this->table = $table;

        $this->fluent = $fluent;
    }

    /**
     * @param int $id
     *
     * @throws Exception
     *
     * @return object|bool
     */
    public function one(int $id)
    {
        $query = $this->fluent->from($this->table->fullName, $id)
            ->asObject();
        $query->execute();

        return $query->fetch();
    }

    /**
     * Returns several rows, defaults to a maximum of 50
     *
     * @param  int    $offset
     * @param  int    $limit
     * @param  string $order
     * @param  string $group
     *
     * @throws Exception
     *
     * @return array
     */
    public function many(int $offset = 0, int $limit = 50, string $order = 'id ASC', string $group = '')
    {
        $query = $this->fluent->from($this->table->fullName);

        if (!empty($group)) {
            $query->groupBy($group);
        }

        if (!empty($order)) {
            $query->orderBy($order);
        }

        $query->limit($limit)
            ->offset($offset)
            ->asObject();

        $query->execute();

        return $query->fetchAll();
    }
}
