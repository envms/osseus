<?php

namespace Envms\Osseus\Dev;

use Envms\Osseus\Architecture\Singleton;
use Envms\Osseus\Server\Environment;
use function debug_backtrace;
use function is_array;
use function is_object;
use function memory_get_peak_usage;
use function memory_get_usage;
use function method_exists;
use function microtime;
use function print_r;
use function round;
use function var_dump;

/**
 * Class Debug
 *
 * @description A collection of quick debugging tools and performance metrics. Many legacy production environments struggle to maintain
 *              consistency with development or staging environments. This class helps where traditional debugging tools are unavailable.
 */
class Debug extends Singleton
{
    /** For adding line breaks to printed data sets */
    protected const LINEBREAK_TEXT = "\n";
    protected const LINEBREAK_HTML = "<br>\n";

    /** For text delimiting and formatting */
    protected const WRAPPER_TEXT = ['--BEGIN-->>>', '<<<~~END~~'];
    protected const WRAPPER_HTML = ['<pre>', '</pre>'];

    public string $linebreak = self::LINEBREAK_HTML;
    public array $wrapper = self::WRAPPER_HTML;

    /**
     * The maximum environment which would still output debug information
     *  Example: if set to STAGING, only production environments would NOT show debug data
     */
    public int $envMax;
    public Environment $environment;

    /**
     * @param array $parameters
     */
    public function initialize(array $parameters): void
    {
        $this->environment = Environment::instance();
        $this->envMax = $parameters[0] ?? Environment::DEVELOPMENT;

        if (str_contains(PHP_SAPI, 'cli')) {
            $this->linebreak = self::LINEBREAK_TEXT;
            $this->wrapper = self::WRAPPER_TEXT;
        }
    }

    /**
     * Prints variable contents
     *
     * @param mixed  $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function pr(mixed $var, string $title = '', string $titleColor = '#35c'): void
    {
        if ($this->isActive()) {
            $var = $this->determineOutput($var);
            echo $this->wrapper[0];

            if ($title !== '') {
                echo "<h2 style='color:{$titleColor}'>{$title}</h2>";
            }

            echo "{$var}{$this->wrapper[1]}{$this->linebreak}{$this->linebreak}";
        }
    }

    /**
     * Prints variable contents and kills script
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function prd(mixed $var, string $title = '', string $titleColor = '#35c'): void
    {
        $this->pr($var, $title, $titleColor);
        $this->kill();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->environment->getCurrent() <= $this->envMax;
    }

    /**
     * Exits and provides additional information on exactly where the script was killed
     */
    public function kill(): void
    {
        if ($this->isActive()) {
            $backtrace = $this->getBacktrace();
            exit("<strong style='font-family:monospace;color:#d03;'>Terminated at {$backtrace['file']}:{$backtrace['line']}</strong>");
        }

        exit('Exited');
    }

    /**
     * @return array
     */
    public function getBacktrace(): array
    {
        return debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3)[2];
    }

    /**
     * Dumps variable contents
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function vd(mixed $var, string $title = '', string $titleColor = '#35c'): void
    {
        if ($this->isActive()) {
            echo $this->wrapper[0];

            if ($title !== '') {
                echo "<h2 style='color:{$titleColor}'>{$title}</h2>";
            }

            var_dump($var);
            echo "{$this->wrapper[1]}{$this->linebreak}{$this->linebreak}";
        }
    }

    /**
     * var_dumps() variable contents and kills script
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function vdd($var, $title = '', $titleColor = '#35c'): void
    {
        $this->vd($var, $title, $titleColor);
        $this->kill();
    }

    /**
     * Prints memory usage for current script.
     */
    public function memory(): void
    {
        if ($this->isActive()) {
            echo 'Memory Usage: ' . round((memory_get_usage() / 1024), 2) . 'kb / Real: '
                . round((memory_get_usage(true) / 1024), 2) . 'kb' . $this->linebreak;
            echo 'Peak Usage: ' . round((memory_get_peak_usage() / 1024), 2) . 'kb / Real: '
                . round((memory_get_peak_usage(true) / 1024), 2) . 'kb' . $this->linebreak;
        }
    }

    /**
     * Prints execution time for current script.
     */
    public function execTime(): void
    {
        if ($this->isActive()) {
            echo 'Execution time: ' . round(((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000), 2) . 'ms' . $this->linebreak;
        }
    }

    /**
     * Displays both execution time and memory usage for current script.
     */
    public function stats(): void
    {
        echo '<div style="position:fixed; bottom:0; right:0; font-family:monospace; font-size:12px;">';
        $this->execTime();
        $this->memory();
        echo '</div>';
    }

    /**
     * Determines which output method is best suited for the data provided.
     *
     * @param mixed $var
     *
     * @return string
     */
    protected function determineOutput(mixed $var): string
    {
        if (is_array($var)) {
            return print_r($var, true);
        }

        if (is_object($var)) {
            if (method_exists($var, '__toString')) {
                return $var->__toString();
            }

            return print_r($var, true);
        }

        return $var;
    }
}
