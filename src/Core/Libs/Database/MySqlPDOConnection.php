<?php

namespace Core\Libs\Database;

class MySqlPDOConnection
{
    /**
     * @var \PDO
     */
    public $dbh;

    /**
     * @var null
     */
    private static $instance = [];

    /**
     * MySqlPDOConnection constructor.
     * @param string $connection
     * @throws \Exception
     */
    private function __construct($connection = '')
    {
        $driver = '';
        $database = '';
        $host = '';
        $username = '';
        $password = '';
        $port = '';
        $charset = '';
        $collation = '';

        if (config('use-database', 'database') === false) {
            throw new \Exception('The using of databases is disabled in the configuration file', 405);
        }

        $dbconfig = include CONFIG_DIR . "database.php";

        if($connection){
            $countVariablesCreated = extract($dbconfig['connections'][$connection], EXTR_OVERWRITE);
            if ($countVariablesCreated != count($dbconfig['connections'][$connection])) {
                throw new \RuntimeException('Extraction failed at line 42: scope modification attempted');
            }

        } else {
            $countVariablesCreated = extract($dbconfig['connections'][$dbconfig['default']], EXTR_OVERWRITE);
            if ($countVariablesCreated != count($dbconfig['connections'][$dbconfig['default']])) {
                throw new \RuntimeException('Extraction failed: scope modification attempted');
            }
          //  extract($dbconfig['connections'][$dbconfig['default']]);

        }

        if($driver !== 'mysql'){
            throw new \PDOException('Support only Mysql driver');
        }

        $dsn = $driver . ':dbname=' . $database . ';' . 'host=' . $host . ';' . 'port='.$port;

        try {

            $this->dbh = new \PDO($dsn, $username, $password,
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$charset}' COLLATE {$collation} "));

            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {

            die(sprintf('MySql DB connection error: "%s"', $e->getMessage()));
        }
    }

    /**
     * @param $connection
     */
    public static function setInstance($connection)
    {
        try {
            self::$instance[$connection] = new MySqlPDOConnection($connection);

        } catch (\Exception $e){
            die($e->getMessage());
        }
    }

    /**
     *
     * @return MySqlPDOConnection|null
     */
    public static function getInstance($connection = 'mysql' )
    {
        if (!isset(self::$instance[$connection])) {
            try {
                self::$instance[$connection] = new self($connection);

            } catch (\Exception $e){
                die($e->getMessage());
            }
        }

        return self::$instance[$connection];
    }

    /**
     * @return  \PDO
     */
    public function getConnection()
    {
        return $this->dbh;
    }
}
