<?php
/**
 * Date: 2.4.2019 г.
 * Time: 17:39
 */

namespace Core\Libs\Support\Facades;

use Core\Libs\Database\DataBase;
use Core\Libs\Database\MysqlPDO;
use Core\Libs\Interfaces\Facade as FasadeInterface;

/**
 * Class DB
 * @method static string pdo()
 * @method static \Core\Libs\Database\MySqlDBQuery table(string $table)
 * @method static \Core\Libs\Database\MySqlDBQuery execute_sql(string $query, array $arguments = null)
 * @package Core\Libs\Support\Facades
 */
class DB extends Facade implements FasadeInterface
{
    /**
     * @var
     */
    public static DataBase $db;

    /**
     * @return MysqlPDO
     * @throws \Exception
     */
    public static function getFacade()
    {
        self::$db = new DataBase();

        return self::$db->get();
    }

    /**
     * @param string $connection
     * @return MysqlPDO
     * @throws \Exception
     */
    public static function connection($connection = '')
    {
        $db = new DataBase();

        $db->connection($connection);
        return $db->get();
    }
}
