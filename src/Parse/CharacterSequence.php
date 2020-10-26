<?php


namespace Envms\Osseus\Parse;

/**
 * Class CharacterSequence - A long-winded way to say the reserved keyword "String"
 * Every method within this class aims to be unicode-safe
 */
class CharacterSequence
{
    /**
     * Splits a string by each character
     *
     * @example - The string 'abcde' becomes ['a', 'b', 'c', 'd', 'e']
     *
     * @param string $string
     *
     * @return array|bool
     */
    public function splitByChar(string $string)
    {
        return preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Splits a string by a number of "sections" ($count)
     *
     * @example - If $count = 2, the string 'qwertyuiop' becomes ['qwert', 'yuiop']
     *
     * @param string $string
     * @param int    $count - How many sections there should be
     *
     * @return array
     */
    public function splitByCount(string $string, int $count): array
    {
        $split = [];
        $strlen = mb_strlen($string);
        $length = ceil($strlen / $count);

        for ($i = 0; $i < $count; $i++) {
            $start = $i * $length;
            $split[] = mb_substr($string, $start, $length);
        }

        return $split;
    }

    /**
     * Splits a string into parts equal in length
     *
     * @example - If $count = 2, the string 'qwertyuiop' becomes ['qw', 'er', 'ty', 'ui', 'op']
     *
     * @param string $string
     * @param int    $count - How long each section should be
     *
     * @return array
     */
    public function splitByLength(string $string, int $count)
    {
        $split = [];
        $strlen = mb_strlen($string);

        for ($i = 0; $i < $strlen; $i += $count) {
            $split[] = mb_substr($string, $i, $count);
        }

        return $split;
    }
}