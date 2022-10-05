<?php
/**
 * Date: 31.3.2019 г.
 * Time: 12:14
 */

namespace Core\Libs\Support\Facades;

use Core\Libs\Encryption\EncryptionService as Encrypter;
/**
 * @method static string encrypt(string $value, bool $serialize = true)
 * @method static string decrypt(string $payload, bool $unserialize = true)
 * @method static string encryptString(string $value)
 * @method static string decryptString(string $payload)
 * @see \Core\Libs\Encryption\Encrypter
 */
class Crypt extends Facade implements \Core\Libs\Interfaces\Facade
{

    public static function getFacade()
    {
        $class = new Encrypter();
        return $class->encrypter;
    }
}
