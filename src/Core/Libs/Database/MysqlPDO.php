<?php
/**
 * Date: 3.4.2019 г.
 * Time: 14:43
 */

namespace Core\Libs\Database;

class MysqlPDO
{
    use MySqlDBQuery;

    /**
     * Model constructor.
     */
    public function __construct($connections = 'mysql')
    {
        $this->connection(
            MySqlPDOConnection::getInstance($connections)->getConnection()
        );
    }
}
