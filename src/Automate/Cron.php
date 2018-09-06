<?php

namespace Envms\Osseus\Automate;

/**
 * Class Cron
 *
 * @todo Separate individual log implementations into their own log classes, not only supporting Monolog
 */

class Cron {

    /** @var mixed */
    protected $log;

    /** @var string */
    protected $logPath;

    /** @var mixed */
    protected $logHandler;

    /** @var \PDO */
    public $pdo;

    /**
     * Cron constructor
     *
     * @param \PDO   $pdo
     * @param        $log
     * @param string $logPath
     */
    public function __construct(\PDO $pdo, $log, string $logPath) {
        $this->pdo = $pdo;

        $this->logHandler = new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::INFO);

        if ($log instanceof \Monolog\Logger) {
            $this->log = $log;
        }
    }

    /**
     * @param string $message
     */
    public function log(string $message) {
        $this->log->pushHandler($this->logHandler);
        $this->log->info($message);
    }

}
