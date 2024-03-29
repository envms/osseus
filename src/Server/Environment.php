<?php

namespace Envms\Osseus\Server;

use Envms\Osseus\Architecture\Singleton;

/**
 * Class Environment
 *
 * The purpose of this class is to set a current runtime environment that can be used across the application
 */
class Environment extends Singleton
{
    public const DEVELOPMENT = 1;
    public const TESTING = 2;
    public const STAGING = 3;
    public const PRODUCTION = 4;

    protected ?int $previous;
    protected ?int $current;

    /**
     * @param array $parameters
     */
    public function initialize(array $parameters): void
    {
        $this->current = $parameters[0] ?? self::PRODUCTION;
    }

    /**
     * @return int
     */
    public function getPrevious(): int
    {
        return $this->previous;
    }

    /**
     * @return int
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @param int $environment
     */
    public function setCurrent(int $environment): void
    {
        $this->previous = $this->current;
        $this->current = $environment;
    }
}
