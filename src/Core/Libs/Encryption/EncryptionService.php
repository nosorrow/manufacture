<?php
/**
 * Date: 30.3.2019 г.
 * Time: 22:15
 */

namespace Core\Libs\Encryption;

use Core\Libs\Support\Facades\Config;

/**
 * Class EncryptionService
 * @package Core\Libs\Encryption
 */
class EncryptionService
{
    public $encrypter;
    /**
     * EncryptionService constructor.
     */
    public function __construct()
    {
        $key = base64_decode(Config::getConfigFromFile('key'));
        $cipher = Config::getConfigFromFile('cipher');
        if ($key ===''){

            throw new \RuntimeException('Encryption key not found in config.php');
        }

        $this->encrypter = new Encrypter($key, $cipher);
    }
}
