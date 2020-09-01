<?php

namespace Envms\Osseus\Dev;


use Envms\Osseus\Architecture\Singleton;
use Envms\Osseus\Server\Environment;

/**
 * Class Debug
 *
 * A collection of quick debugging tools and performance metrics
 */
class Debug extends Singleton
{
    /** @const - used to add ascii line breaks to printed data sets */
    protected const LINEBREAK_TEXT = "\r\n";
    /** @const - used to add line breaks to printed data sets */
    protected const LINEBREAK_HTML = "<br>\r\n";
    /** @const - html friendly formatting */
    protected const PRE = ['<pre>', '</pre>'];

    /** @var Environment */
    public Environment $environment;
    /** @var int - the maximum environment which would still output debug information
     *             Example: if set to STAGING, only production environments would NOT show debug data
     */
    public int $envMax;
    /** @var string */
    public string $linebreak;

    /**
     * @param array $parameters
     */
    protected function initialize(array $parameters = []): void
    {
        $this->linebreak = (strpos(php_sapi_name(), 'cli') !== false) ? self::LINEBREAK_TEXT : self::LINEBREAK_HTML;
        $this->envMax = $parameters[0];

        $this->environment = Environment::instance();
    }

    /**
     * Exits and provides additional information on exactly where the script was killed
     */
    public function ks()
    {
        if ($this->isActive()) {
            $backtrace = $this->getBacktrace();
            exit("<b style='font-family:Consolas,monospace;color:#c04;'>Application terminated ({$backtrace['file']} - Line {$backtrace['line']})</b>");
        } else {
            exit('Exited');
        }
    }

    /**
     * Prints variable contents
     *
     * @param mixed  $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function p($var, $title = '', $titleColor = '#c22')
    {
        if ($this->isActive()) {
            $var = $this->determineOutput($var);
            echo self::PRE[0];

            if ($title !== '') {
                echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
            }

            echo $var
                . self::PRE[1]
                . $this->linebreak
                . $this->linebreak;
        }
    }

    /**
     * Prints variable contents and kills script
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function pd($var, $title = '', $titleColor = '#c22')
    {
        $this->p($var, $title, $titleColor);
        $this->ks();
    }

    /**
     * var_dumps() variable contents
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function vd($var, $title = '', $titleColor = '#c22')
    {
        if ($this->isActive()) {
            echo self::PRE[0];

            if ($title !== '') {
                echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
            }

            var_dump($var);
            echo self::PRE[1]
                . $this->linebreak
                . $this->linebreak;
        }
    }

    /**
     * var_dumps() variable contents and kills script
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public function vdd($var, $title = '', $titleColor = '#c22')
    {
        $this->vd($var, $title, $titleColor);
        $this->ks();
    }

    /**
     * Prints memory usage for current script.
     */
    public function memory()
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
    public function execTime()
    {
        if ($this->isActive()) {
            echo 'Execution time: ' . round(((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000), 2) . 'ms' . $this->linebreak;
        }
    }

    /**
     * Displays both execution time and memory usage for current script.
     */
    public function stats()
    {
        echo '<div style="position:fixed; bottom:0; right:0; font-family:monospace; font-size:12px;">';
        $this->execTime();
        $this->memory();
        echo '</div>';
    }

    /**
     * @return array
     */
    public function getBacktrace(): array
    {
        return debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3)[2];
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->environment->getCurrent() <= $this->envMax;
    }

    /**
     * Determines which output method is best suited for the data provided.
     *
     * @param mixed $var
     *
     * @return string
     */
    protected function determineOutput($var)
    {
        if (is_array($var)) {
            return print_r($var, true);
        } elseif (is_object($var)) {
            if (method_exists($var, '__toString')) {
                return $var->__toString();
            } else {
                return print_r($var, true);
            }
        }

        return $var;
    }
}
