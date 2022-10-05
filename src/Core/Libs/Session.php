<?php

namespace Core\Libs;

use Core\Libs\Support\Arr;
use Core\Libs\Support\Facades\Config;

/*
 * $session = new Session();
 * $session->store('name', 'value');
 */

/**
 * Class Session
 * @package Core\Libs
 */
class Session
{
    private static $instance = null;

    public $session_save_path;

    public $config;

    protected $session_bag;

    /**
     * Session constructor.
     *
     * @throws \Exception
     */
    protected function __construct()
    {
        $driver = Config::getConfigFromFile('session_handler');

        if ($driver === 'database') {
            include_once SYSTEM_DIR . 'Libs\Session\DataBaseSessionHandler.php';
        }

        $name = Config::getConfigFromFile('session_name');
        $lifetime = Config::getConfigFromFile('session_cookie_lifetime');
        $session_save_path = Config::getConfigFromFile('session_save_path');
        $domain = null;
        $secure = Config::getConfigFromFile('session_secure');
        $this->session_save_path = $session_save_path;

        if (
            !realpath($session_save_path) &&
            !mkdir($session_save_path, 0777, true) &&
            !is_dir($session_save_path)
        ) {
            throw new \RuntimeException(
                sprintf('Directory "%s" was not created', $session_save_path)
            );
        }

        session_name($name);
		if ($session_save_path) {
			session_save_path($session_save_path);
		}
        session_set_cookie_params($lifetime, '/', $domain, $secure, true);
        session_start();

        $this->session_bag = !empty($_SESSION) ? $_SESSION : null;
        $this->session_regenerate(config('session_regenerate'));
        // garbage collection
        if ($this->sessionLottarygc(1, 200)) {
            $this->gc();
        }
    }

    public function session_regenerate($_bool, $_time = 300)
    {
        // The first session time;
        if (!isset($_SESSION['last_regen'])) {
            $_SESSION['last_regen'] = time();
        }

        $session_regen_time = $_time;

        // Only regenerate session id if last_regen is older than the given regen time.
        if ($_SESSION['last_regen'] + $session_regen_time < time()) {
            $_SESSION['last_regen'] = time();

            session_regenerate_id((bool) $_bool);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getConfig($data)
    {
        return $this->config[$data];
    }

    /**
     * @param mixed $config
     * @param $data
     */
    public function setConfig($config, $data)
    {
        $this->config[$config] = $data;
    }

    /**
     * @param $key
     * @param null $data
     */
    public function store($key, $data = null)
    {
        if (!is_array($key) && is_object($data)) {
            Arr::set($_SESSION, $key, $data);

            return;
        }

        if (!is_array($key)) {
            $key = [$key => $data];
        }

        foreach ($key as $k => $value) {
            Arr::set($_SESSION, $k, $value);
        }
    }

    /**
     * @param $key
     * @param $data
     */
    public function set($key, $data)
    {
        $this->store($key, $data);
    }

    /**
     * Искам на изхода сесията да ми е чиста;
     * @param array $array
     * @return array
     */
    protected function recursiveCleanSession(array $array)
    {
        array_walk_recursive($array, function (&$value) {
            $value = XssSecure::xss_clean($value);
        });

        return $array;
    }

    /**
     * @return array
     */
    public function get_all()
    {
        $session = $this->recursiveCleanSession($_SESSION);

        return $session;
    }

    /**
     * Alias of get_all()
     * @return array
     */
    public function all()
    {
        return $this->get_all();
    }

    /**
     * @param $key
     * @return null
     */
    public function getData($key, $default = null)
    {
        $session = $this->recursiveCleanSession($_SESSION);

        return Arr::get($session, $key, $default);
    }

    /**
     * @param $key
     * @return bool
     */

    public function has($key, $default = false)
    {
        $array = Arr::get($_SESSION, $key, $default);

        return (bool) $array;
    }

    /**
     * @param $name
     * @param $value
     */
    public function push($name, $value)
    {
        $array = (array) Arr::get($_SESSION, $name, []);
        $array[] = $value;
        $this->store($name, $array);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($_SESSION, $key, $default);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setFlash($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getFlash($key)
    {
        $flash = [];

        if (isset($_SESSION['flash'][$key])) {
            $flash[$key] = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
        } else {
            $flash[$key] = null;
        }

        return $flash[$key];
    }

    /**
     * destroy
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * delete
     * @param $key
     *
     */
    public function delete($key)
    {
        Arr::forget($_SESSION, $key);
    }

    /**
     * gc
     */
    protected function gc()
    {
        foreach (glob($this->session_save_path . "/sess_*") as $filename) {
            if (
                filemtime($filename) + session_get_cookie_params()['lifetime'] <
                time()
            ) {
                @unlink($filename);
            }
        }
    }

    /**
     * @param int $min
     * @param $max
     * @return bool
     * @throws \Exception
     */
    protected function sessionLottarygc(int $min, int $max): bool
	{
        return random_int($min, $max) === $min;
    }

    /**
     * @return Session|null
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
