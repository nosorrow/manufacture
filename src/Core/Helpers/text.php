<?php

if (!function_exists('word_limit')) {
    /**
     * @param $str
     * @param int $limit
     * @param string $end_char
     * @return string
     */
    function word_limit($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) === '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int)$limit . '}/', $str, $matches);

        if (strlen($str) === strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }
}

/**
 * @param $text
 * @param $n
 * @param string $trimmaker
 * @return string
 */
function get_the_excerpt($text, $n, $trimmaker='[...]'){

    $offset = 0;

    for ($i = 0; $i < $n-1; $i++) {
        $pos = strpos($text, ' ', $offset);

        if ($pos !== false) {
            $offset = $pos + strlen(' ');
        } else {
            return $text;
        }
    }

    return mb_strimwidth($text, 0, $offset+strlen($trimmaker), $trimmaker);

}
