<?php
/**
 * Date: 3.4.2019 г.
 * Time: 13:04
 */

namespace Core\Libs\Database;

use Core\Libs\Interfaces\DbStrategy;

class DataBase implements DbStrategy
{
    public $dbconfig;

    private $connection;

	/**
	 * DataBase constructor.
	 * @throws \Exception
	 */
    public function __construct()
    {
        $file = CONFIG_DIR . "database.php";
        if (file_exists($file)) {
            $this->dbconfig = include $file;

        } else {
            throw new \Exception(sprintf('(%s) configuration file not found', $file), 404);
        }

        if($this->dbconfig['use-database'] !== true){
            throw new \Exception('Using database is disabled in configuration file');
        }
    }

    /**
     * @return MysqlPDO
     * @throws \Exception
     */
    public function get()
    {
        $connection = $this->connection ?: $this->dbconfig['default'];

        if (!isset($this->dbconfig['connections'][$connection]) ||
            !$this->dbconfig['connections'][$connection]
        ) {
            throw new \PDOException(sprintf('(%s) connections is missing in database configuration file',
                $connection));
        }

        $driver = $this->dbconfig['connections'][$connection]['driver'];

        if ($driver === 'mysql') {
            return new MysqlPDO($connection);

        }

        throw new \PDOException(sprintf('(%s) is not supprted database  driver', $driver));
    }

    /**
     * @param $connection
     * @return $this
     */
    public function connection($connection)
    {
        $this->connection = $connection;
        return $this;
    }
}
