<?php
namespace Envms\Osseus\Automate;

/**
 * Class Cron
 *
 * @todo Separate individual log implementations into their own log classes, not only supporting Monolog
 */

class Cron {

    protected $log;

    public $pdo;

    public function __construct(\PDO $pdo, $log) {
        $this->pdo = $pdo;

        if ($log instanceof \Monolog\Logger) {
            $this->log = $log;
        }
    }

    // logging function
    public function log(string $logPath, string $message) {
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::INFO));
        $this->log->info($message);
    }

}
