<?php

namespace Core\Libs\Support\Url;
use Core\Libs\XssSecure;
use Core\Libs\Support\Facades\Config;
/**
 * Class UrlParser
 * @package Core\Libs\Support\Url
 */
final class UrlParser
{
    /**
     * @var
     */
    public static $base_url;
    /**
     * @var
     */
    public static $url;
    /**
     * @var
     */
    public static $uri;
    /**
     * @var
     */
    public static $query;
    /**
     * @var
     */
    public static $script_path;
    /**
     * @var
     */
    public static $request;

    /**
     * @param mixed $request
     */
    public static function setRequest($request)
    {
        self::$request = $request;
    }

    /**
     * @param mixed $script_path
     */
    public static function setScriptPath($script_path)
    {
        self::$script_path = $script_path;
    }


    protected static function baseUrlInit()
    {
        $base_url = Config::getConfigFromFile('base_url');

        // ако не е хардкоднат $base_url го го генерираме от глобалната променлива $_SERVER
        $request_scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : Config::getConfigFromFile('REQUEST_SCHEME');

        if(!$request_scheme || !isset($request_scheme)) {
            throw new \Exception("UrlParser Error: Server REQUEST_SCHEME is not defined", 500);
        }

        if (!empty($base_url)) {
            self::$base_url = $base_url;

        } else {
            self::$base_url = $request_scheme . '://' . $_SERVER['HTTP_HOST'] .
                substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
        }

        $n = new UrlNormalizer(self::$base_url);

        self::$base_url = rtrim($n->normalize(), "/");

    }

    /**
     *  Initialize Uri string
     */
    protected static function requestUriInit() : void
    {
    /*    $request = urldecode(htmlentities($_SERVER['REQUEST_URI']));

        $request = trim($request, '/');

        $normalize = new UrlNormalizer($request);

        $request = $normalize->normalize();

        $this->script_path = $_SERVER['SCRIPT_NAME'];*/

        $request = XssSecure::xss_clean($_SERVER['REQUEST_URI']);
        $request = trim($request, '/');

        self::setRequest($request);
        self::setScriptPath($_SERVER['SCRIPT_NAME']);

        if (!empty($request)) {
            self::removeIndex();
            // ако се извиква от пр. http://localhost/MyScripts/booking-room/public/search
            $_uri = trim(substr($request, strlen(self::$script_path)), '/');
            // Ако има GET query  : http://booking-room.dev/booking?bar=baz
            //искам да uri = booking , query = bar=zas
            if (strpos($_uri, '?') !== false) {
                self::$uri = strstr($_uri, '?', true);
                self::$query = strstr($_uri, '?');
            } else {
                self::$uri = $_uri;
            }
        } else {
            self::$uri = "";
        }
    }

    /**
     * @return mixed
     */
    public static function getUri()
    {
        self::requestUriInit();
        return self::$uri;
    }

    /**
     * @return string
     */
    public static function getBaseUrl()
    {
        self::baseUrlInit();
        return self::$base_url;
    }

    /**
     * @return string
     */
    public static function getFullUrl()
    {
        self::getBaseUrl();
        self::requestUriInit();
        $query = self::$query ?? null;
        return self::$base_url . '/' . self::$uri . $query;
    }

    protected static function removeIndex()
    {
        self::$script_path = substr(str_replace("index.php", "", self::$script_path), 1);

    }
    
}
