<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.3.2018 г.
 * Time: 19:36 ч.
 */

namespace Core\Libs;

use Core\Bootstrap\Router;

class Redirector extends Url
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var
     */
    private $targetUrl;

    /**
     * Redirector constructor.
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;

    }

    /**
     * $this->route( 'routename', [ param, param-1 ] )->redirect();
     * @param null $uri
     */
    protected function redirect($uri = null)
    {
        if ($uri === null) {

            $uri = $this->targetUrl;
        }

        header("location:" . $uri);
        exit;
    }

    /**
     * redirect()->to('dashboard/new/user)->with('msg', 'create new user')
     * @param $route
     * @return $this
     */
    public function to($route)
    {
        $this->targetUrl = $this->getSiteUrl($route);
        return $this;
    }

    /**
     * redirect()->route('routeName')->with('msg', 'create new user')
     * @param $routename
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function route($routename, array $params = [], $method = 'get')
    {
        try{
            $uri = $this->router->route($routename, $params, $method)->route;
            $this->targetUrl = $this->getSiteUrl($uri);

        } catch (\Exception $e){
            die($e->getMessage());
        }

        return $this;
    }

    /**
     *  redirect()->away('http://google.com')
     * @param $url
     * @return Redirector
     */
    public function away($url)
    {
        $this->targetUrl = $url;

        return $this;
    }
    /**
     *  redirect()->with('msg', 'Login Failed')->route('home')
     * @param $key
     * @param $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        $key = is_array($key) ? $key : [$key => $value];

        foreach ($key as $k => $v) {
            $this->session->setFlash($k, $v);
        }

        return $this;
    }

    /**
     * redirect()->back()->with('msg', 'Testing');
     * Redirect to previous
     */
    public function back()
    {
        $this->targetUrl = $this->_previous;
        return $this;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->redirect();
    }
}
