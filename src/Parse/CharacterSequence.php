<?php


namespace Envms\Osseus\Parse;

/**
 * Class CharacterSequence - A long winded way to say the reserved word "String"
 */
class CharacterSequence
{
    public const SPLIT_EACH = 1;
    public const SPLIT_SECTIONS = 2;
    public const SPLIT_LENGTH = 3;

    /**
     * A unicode-safe method of splitting a string
     *
     * @param string $string
     * @param int $type    - Method by which to split the string. Can be one of SPLIT_EACH,
     *                       SPLIT_SECTIONS or SPLIT_LENGTH
     * @param int $count   - How long each section should be (if the $sections argument is not set)
     *
     * @return array|bool
     */
    public function split(string $string, int $type = self::SPLIT_EACH, int $count = 1)
    {
        $split = [];
        $strlen = mb_strlen($string);

        // if only a string is passed, split the string character by character
        if ($type === self::SPLIT_EACH) {
            return preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);
        }

        if ($type === self::SPLIT_SECTIONS) {
            // if the sections argument is set, overwrite the length argument with an equal split value
            $length = ceil($strlen / $count);

            for ($i = 0; $i < $count; $i++) {
                $start = $i * $length;
                $split[] = mb_substr($string, $start, $length);
            }

            return $split;
        }

        if ($type === self::SPLIT_LENGTH) {
            // split by the requested chunk size
            for ($i = 0; $i < $strlen; $i += $count) {
                $split[] = mb_substr($string, $i, $count);
            }

            return $split;
        }

        // invalid split type selected
        return false;
    }
}