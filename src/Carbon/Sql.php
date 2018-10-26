<?php

namespace Envms\Osseus\Carbon;

use Envms\Osseus\Database\{Identifier, Table};
use Envms\Osseus\Interfaces\Database\Carbon as CarbonInterface;

/**
 * Class Sql
 */
class Sql implements CarbonInterface
{
    /** @var Table - The combined strings of $module, $divider and $table to define the data source */
    protected $table = null;
    /** @var Identifier */
    protected $identifier = null;

    /** @var \PDO */
    public $pdo;

    /**
     * @param \PDO $pdo
     * @param Table $table
     * @param Identifier $identifier
     */
    public function __construct(\PDO $pdo, Table $table, Identifier $identifier)
    {
        $this->table = $table;
        $this->identifier = $identifier;

        $this->pdo = $pdo;
    }

    /**
     * @param int $id
     *
     * @throws \PDOException
     *
     * @return object|bool
     */
    public function one(int $id)
    {
        $primaryKey = $this->identifier->getPrimaryKey($this->table->fullName);

        $statement = $this->pdo->prepare("SELECT * FROM {$this->table->fullName} WHERE {$primaryKey} = :id");
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Returns several rows, defaults to a maximum of 50
     *
     * @param  int    $offset
     * @param  int    $limit
     * @param  string $order
     * @param  string $group
     *
     * @return array
     */
    public function many(int $offset = 0, int $limit = 50, string $order = 'id ASC', string $group = '')
    {
        $query = "SELECT * FROM {$this->table->fullName}";

        if (!empty($group)) {
            $query .= " GROUP BY {$group}";
        }

        if (!empty($order)) {
            $query .= " ORDER BY {$group}";
        }

        $query .= " LIMIT {$offset}, {$limit}";

        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }
}
