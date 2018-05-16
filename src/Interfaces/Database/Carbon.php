<?php

namespace Envms\Osseus\Interfaces\Database;

/**
 * Interface Carbon
 *
 * Carbon acts as an intermediary between the database and model/collections. In the Env Framework, the model
 * does not care how or where it's getting its data from, only about storing and managing the data.
 *
 * @todo Refactor interface to include methods offset, limit, order and group, and not manage with all() method
 */
interface Carbon {

    /**
     * @param int    $offset
     * @param int    $limit
     * @param string $order
     * @param string $group
     */
    public function all(int $offset, int $limit, string $order, string $group);

}
