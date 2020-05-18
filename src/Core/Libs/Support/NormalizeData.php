<?php
/**
 * Date: 27.2.2019 г.
 * Time: 19:00
 */

namespace Core\Libs\Support;


use Core\Libs\XssSecure;

class NormalizeData
{
    /**
     * filter data
     *
     * @param $data
     * @param $types
     * @return bool|float|int|string
     */
    public static function filter($data, $types)
    {
        $types = explode('|', $types);

        if (is_array($types)) {

            foreach ($types as $value) {
                switch ($value) {
                    case 'int':
                        $data = (int)$data;
                        break;
                    case 'float':
                        $data = (float)$data;
                        break;
                    case 'double':
                        $data = (double)$data;
                        break;
                    case 'bool':
                        $data = (bool)$data;
                        break;
                    case 'string':
                        $data = (string)$data;
                        break;
                    case 'addslashes':
                        $data = addslashes($data);
                        break;
                    case 'htmlentities':
                        $data = htmlentities($data, ENT_QUOTES, "UTF-8");
                        break;
                    case 'strip_tags':
                        $data = strip_tags($data);
                        break;
                    case 'strip_interval':
                        $data = trim(preg_replace('/\s\s+/', ' ', $data));
                        break;
                    case 'html_entity_decode':
                        $data = html_entity_decode($data);
                        break;
                    case 'trim':
                        $data = trim($data);
                        break;
                    case 'urlencode':
                        $data = urlencode($data);
                        break;
                    case 'xss':
                        $data = XssSecure::xss_clean($data);
                        break;
                    case 'htmlspecialchars':
                        $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
                        break;
                    case 'escapeshellcmd':
                        $data = escapeshellcmd($data);
                        break;
                    case 'escapeshellarg':
                        $data = escapeshellarg($data);
                        break;
                }
            }
        }

        return $data;
    }
}
