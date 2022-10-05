<?php


namespace Core\Libs;

use Core\Libs\Support\Arr;
use Exception;

class AppConfig
{
    const DOMAIN = 'config';
    private static $instance = null;
    protected $files;

    /**
     * Config constructor.
     * @param null $instance
     */
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function get($key, $domain = self::DOMAIN)
    {
        return $this->getConfigFromFile($key, $domain);
    }

    /**
     * @param $name
     * @param string $domain
     * @return mixed|null
     * @throws Exception
     */
    public function getConfigFromFile($name, $domain = self::DOMAIN)
    {
        $configArr = $this->configArray();

        $config = Arr::get($configArr, $domain . '.' . $name);

        return $config ?? null;

    }

    /**
     * @return array
     */
    protected function configArray()
    {
        // One array for all configuration files
        // 'domain'=>[key=>config]...
        $config = [];
        $files = $this->parseConfigFiles();

        foreach ($files as $filename => $filepath) {
            $config[$filename] = include $filepath;
        }

        return $config;
    }

    /**
     * @return mixed
     */
    protected function parseConfigFiles()
    {
        $glob = glob(CONFIG_DIR . '*.php');

        foreach ($glob as $value) {
            $files[pathinfo($value)['filename']] = $value;
        }

        return $files;
    }

    /**
     * Singletons should not be restorable from strings.
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone()
    {
    }

}
