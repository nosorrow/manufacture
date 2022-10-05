<?php
/**
 * Base Model
 */

namespace Core;

use Core\Libs\Database\DataBase;

class Model
{
    /**
     * @var string
     */
    public $db;

    /**
     * Model constructor.
     */
    public function __construct($connections = 'mysql')
    {
        $db = new DataBase();
        $db->connection($connections);
        $this->db = $db->get();

    }
}
