<?php

namespace Core\Libs;

use Core\Libs\Support\Url\UrlParser;
/**
 * Class Url
 * @package Core\Libs
 */
class Url
{
    protected static $instance;

    public $urlnormalizer;

    public $session;

    public $site_url;

    public $base_url;

    public $index_page;

    public $parsedRequestUrl;

    public $_previous;

    /**
     * Url constructor.
     */
    protected function __construct()
    {
        $this->session = Session::getInstance();
        $this->urlInit();
        $this->session->store('_previous', $this->getReferer());
    }

    /**
     *
     */
    private function urlInit()
    {

        $this->base_url = UrlParser::getBaseUrl();

        if ($this->session->getData('_previous')) {
            $this->_previous = $this->session->getData('_previous');

        }
    }

    /**
     * @param $path
     * @return bool
     */
    public function isValidUrl($path)
    {
        if (!preg_match('~^(#|//|https?://|mailto:|tel:)~', $path)) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param null $uri
     * @return mixed
     * @throws \Exception
     */
    public function getSiteUrl($uri = null)
    {
        $uri = XssSecure::xss_clean($uri);
        $uri = ($uri) ?   '/' . $uri : '';

        return UrlParser::getBaseUrl() . $uri;
    }


    /**
     *
     * @param null $uri
     * @return bool|string
     * @throws \Exception
     */
    public function getMediaUrl($uri = null)
    {
        return substr(str_replace("index.php/", "", $this->getSiteUrl($uri)), 0);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function request()
    {
        $this->parsedRequestUrl = parse_url(trim(UrlParser::getFullUrl(), '/'));

        return $this;
    }

    /**
     *
     * @return string
     * @throws \Exception
     */
    protected function requestUrl()
    {
        return rtrim($this->getBaseUrl(), '/') . $this->request()->path();
    }

    public function current()
    {
        return $this->requestUrl();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function full()
    {

        /*$url = $this->requestUrl['scheme'] . '://';
        $url .= $this->requestUrl['host'];
        $url .= $this->requestUrl['path'];
        if(isset($this->requestUrl['query'])){
            $url .= '?' . $this->requestUrl['query'];
        }*/
        return rtrim(UrlParser::getFullUrl(), '/');// === $this->requestUrlPath();
    }
    
    /**
     * @return mixed
     */
    public function path()
    {
        return $this->parsedRequestUrl['path'] ?? '';
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return $this->parsedRequestUrl['query'] ?? '';
    }

    /**
     * @return mixed
     */
    public function host()
    {
        return $this->parsedRequestUrl['host'];
    }

    /**
     * @return mixed
     */
    public function scheme()
    {
        return $this->parsedRequestUrl['scheme'];
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
        $_referer = new UrlNormalizer($referer);

        return $_referer->normalize();
    }

    /**
     * @return null
     */
    public function previous()
    {
        return $this->_previous;
    }

    /**
     * Redirect to previous
     */
    public function redirect_back()
    {
        header("location:" . $this->_previous);
        exit;
    }

    /**
     * @param string $text
     * @return string
     */
    public function link_back($text = 'back', $button = false)
    {
        if ($button !== false) {
            $btn = $button;
        } else {
            $btn = '';
        }

        return '<a class="' . $btn . '" href="' . $this->_previous . '">' . $text . '</a>';
    }


    /**
     * @param int $delay
     */
    public function refresh($delay = 0)
    {
        header('Refresh:' . $delay);
    }
    /**
     * @return Url
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }
}
