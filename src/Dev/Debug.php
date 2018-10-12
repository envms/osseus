<?php

namespace Envms\Osseus\Dev;

/**
 * Class Debug
 *
 * A collection of quick debugging tools and performance metrics
 */
class Debug
{

    /** @const - used to add ascii line breaks to printed data sets */
    const LINEBREAK_TEXT = "\r\n";
    /** @const - used to add html line breaks to printed data sets */
    const LINEBREAK_HTML = "<br>";
    /** @const - used to add line breaks to printed data sets */
    const LINEBREAK = "<br>\r\n";
    /** @const - html friendly formatting */
    const PRE = ['<pre>', '</pre>'];

    /**
     * Exits and provides additional information on exactly where the script was killed
     */
    public static function ks()
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1];
        exit("<b style='font-family:Consolas,monospace;color:#c04;'>Application terminated ({$backtrace['file']} - Line {$backtrace['line']})</b>");
    }

    /**
     * Prints variable contents
     *
     * @param mixed  $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public static function p($var, $title = '', $titleColor = '#c22')
    {
        $var = self::determineOutput($var);
        echo self::PRE[0];

        if ($title !== '') {
            echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
        }

        echo $var
            . self::PRE[1]
            . self::LINEBREAK
            . self::LINEBREAK;
    }

    /**
     * Prints variable contents and kills script
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public static function pd($var, $title = '', $titleColor = '#c22')
    {
        $var = self::determineOutput($var);
        echo self::PRE[0];

        if ($title !== '') {
            echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
        }

        echo $var
            . self::PRE[1]
            . self::LINEBREAK
            . self::LINEBREAK;

        self::ks();
    }

    /**
     * var_dumps() variable contents
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public static function vd($var, $title = '', $titleColor = '#c22')
    {
        echo self::PRE[0];

        if ($title !== '') {
            echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
        }

        var_dump($var);
        echo self::PRE[1]
            . self::LINEBREAK
            . self::LINEBREAK;
    }

    /**
     * var_dumps() variable contents and kills script
     *
     * @param        $var - variable to print
     * @param string $title
     * @param string $titleColor
     */
    public static function vdd($var, $title = '', $titleColor = '#c22')
    {
        echo self::PRE[0];

        if ($title !== '') {
            echo "<h3 style='color:{$titleColor}'>{$title}</h3>";
        }

        var_dump($var);
        echo self::PRE[1]
            . self::LINEBREAK
            . self::LINEBREAK;

        self::ks();
    }

    /**
     * Prints memory usage for current script.
     */
    public static function memory()
    {
        echo 'Memory Usage: ' . round((memory_get_usage() / 1024), 2) . 'kb / Real: ' . round((memory_get_usage(true) / 1024),
                2) . 'kb' . self::LINEBREAK;
        echo 'Peak Usage: ' . round((memory_get_peak_usage() / 1024), 2) . 'kb / Real: ' . round((memory_get_peak_usage(true) / 1024),
                2) . 'kb' . self::LINEBREAK;
    }

    /**
     * Prints execution time for current script.
     */
    public static function execTime()
    {
        echo 'Execution time: ' . round(((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000), 2) . 'ms' . self::LINEBREAK;
    }

    /**
     * Displays both execution time and memory usage for current script.
     */
    public static function stats()
    {
        echo '<div style="position:fixed; bottom:0; right:0; font-family:Consolas,monospace; font-size:12px;">';
        self::execTime();
        self::memory();
        echo '</div>';
    }

    /**
     * Determines which output method is best suited for the data provided.
     *
     * @param mixed $var
     *
     * @return string
     */
    public static function determineOutput($var)
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
