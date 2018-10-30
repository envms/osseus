<?php

namespace Envms\Osseus\Interfaces\Database;

/**
 * Interface Carbon
 *
 * Carbon acts as an intermediary between the database and model/collections. In the Env Framework, the model
 * does not care how or where it's getting its data from, only about storing and managing the data.
 */
interface Carbon
{
    /**
     * @param int $id
     *
     * @return array|object
     */
    public function one(int $id);

    /**
     * @param int    $offset
     * @param int    $limit
     * @param string $order
     * @param string $group
     *
     * @return array|object
     */
    public function many(int $offset, int $limit, string $order, string $group);
}
