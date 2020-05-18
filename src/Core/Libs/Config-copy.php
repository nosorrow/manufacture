<?php

class ConfigCopy
{

    protected static $files;

    const DOMAIN = 'config';

    /**
     * @param $name
     * @param string $domain
     * @return array|mixed|null
     */
    public static function getConfigFromFile($name, $domain = self::DOMAIN)
    {
        $configArr  = self::configArray();

        $config = Arr::get($configArr, $domain . '.' . $name);

        if (!isset($config)) {
            return null;
     }

        return $config;

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
        // One array for all configuration files
        // 'domain'=>[key=>config]...
        $config = [];
        $files = self::parseConfigFiles();

        foreach ($files as $filename=>$filepath){
            $config[$filename] = include $filepath;
        }

        return $config;
    }
}
