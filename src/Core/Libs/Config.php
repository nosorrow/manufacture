<?php


namespace Core\Libs;

use Core\Libs\Support\Arr;

class Config
{
    public static $config;

    protected static $files;

    const DOMAIN = 'config';


    /**
     * @param $name
     * @param string $domain
     * @return array|mixed
     * @throws \Exception
     */
    public static function getConfigFromFile($name, $domain = self::DOMAIN)
    {
        $configArr  = self::configArray();
        $config = Arr::get($configArr, $domain . '.' . $name);

        if (!isset($config)) {
            throw new \Exception("The configuration key '{$name}' is not defined", 500);

        }

        self::$config = $config;

        return self::$config;

    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public static function get($key, $domain = self::DOMAIN)
    {
        return self::getConfigFromFile($key, $domain);
    }

    /**
     * @return mixed
     */
    protected static function parseConfigFiles()
    {
        $glob = glob(CONFIG_DIR . '*.php');

        foreach ($glob as $value) {
            $files[pathinfo($value)['filename']] = $value;
        }

        return $files;
    }

    /**
     * @return array
     */
    protected static function configArray()
    {
        // One array with from all configuration files
        // 'domain'=>[key=>config]...
        $config = [];
        $files = self::parseConfigFiles();

        foreach ($files as $filename=>$filepath){
            $_config[$filename] = include $filepath;

            $config = array_merge($config, $_config);
        }
        return $config;
    }
}
