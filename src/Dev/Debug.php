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
    /** @const - used to add html line breaks to printed data sets */
    protected const LINEBREAK_HTML = "<br>";
    /** @const - used to add line breaks to printed data sets */
    protected const LINEBREAK_BOTH = "<br>\r\n";
    /** @const - html friendly formatting */
    protected const PRE = ['<pre>', '</pre>'];

    /** @var Environment */
    public $environment;

    /** @var int */
    public $envMax;
    /** @var string */
    public $linebreak;

    /**
     * @return Debug|mixed
     */
    public static function instance()
    {
        return parent::instance();
    }

    /**
     * @param int $envMax  - the maximum environment which would still output debug information. An example would be if this
     *                       was set to STAGING, only production environments would NOT show debug info
     * @param string $sapi - the current server API
     */
    public function init(int $envMax, string $sapi)
    {
        $this->linebreak = (strpos($sapi, 'cli') !== false) ? self::LINEBREAK_TEXT : self::LINEBREAK_BOTH;
        $this->envMax = $envMax;

        $this->environment = Environment::instance();
    }

    /**
     * Exits and provides additional information on exactly where the script was killed
     */
    public function ks()
    {
        if ($this->environment->getCurrent() <= $this->envMax) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1];
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
        if ($this->environment->getCurrent() <= $this->envMax) {
            $var = self::determineOutput($var);
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
        if ($this->environment->getCurrent() <= $this->envMax) {
            $var = self::determineOutput($var);
            echo self::PRE[0];

            if ($title !== '') {
                echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
            }

            echo $var
                . self::PRE[1]
                . $this->linebreak
                . $this->linebreak;

            self::ks();
        }
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
        if ($this->environment->getCurrent() <= $this->envMax) {
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
        if ($this->environment->getCurrent() <= $this->envMax) {
            echo self::PRE[0];

            if ($title !== '') {
                echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
            }

            var_dump($var);
            echo self::PRE[1]
                . $this->linebreak
                . $this->linebreak;

            self::ks();
        }
    }

    /**
     * Prints memory usage for current script.
     */
    public function memory()
    {
        echo 'Memory Usage: ' . round((memory_get_usage() / 1024), 2) . 'kb / Real: ' . round((memory_get_usage(true) / 1024),
                2) . 'kb' . $this->linebreak;
        echo 'Peak Usage: ' . round((memory_get_peak_usage() / 1024), 2) . 'kb / Real: ' . round((memory_get_peak_usage(true) / 1024),
                2) . 'kb' . $this->linebreak;
    }

    /**
     * Prints execution time for current script.
     */
    public function execTime()
    {
        echo 'Execution time: ' . round(((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000), 2) . 'ms' . self::$linebreak;
    }

    /**
     * Displays both execution time and memory usage for current script.
     */
    public function stats()
    {
        if ($this->environment->getCurrent() <= $this->envMax) {
            echo '<div style="position:fixed; bottom:0; right:0; font-family:Consolas,monospace; font-size:12px;">';
            self::execTime();
            self::memory();
            echo '</div>';
        }
    }

    /**
     * Determines which output method is best suited for the data provided.
     *
     * @param mixed $var
     *
     * @return string
     */
    public function determineOutput($var)
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
