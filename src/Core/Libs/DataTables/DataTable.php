<?php
namespace Core\Libs\DataTables;
/*
 * $data_table->whereAll('status', '=', 'canceled')
 * ->fetch('datatables', 'reservation_id', $columns);
 */
class DataTable
{
    public $whereAll;

    /**
     * @param $field
     * @param $condition
     * @param $val
     * @return $this
     */
    public function whereAll($field, $condition, $val)
    {
        $this->whereAll = $field . $condition . "'" .$val . "'";

        return $this;
    }

    /**
     * @param $_table
     * @param $primary_Key
     * @param $columns
     */
    public function fetch($_table, $primary_Key, $columns)
    {

        header("Content-Type: text/json; charset=UTF-8");

        /*
         * DataTables example server-side processing script.
         *
         * Please note that this script is intentionally extremely simply to show how
         * server-side processing can be implemented, and probably shouldn't be used as
         * the basis for a large complex system. It is suitable for simple use cases as
         * for learning.
         *
         * See http://datatables.net/usage/server-side for full details on the server-
         * side processing requirements of DataTables.
         *
         * @license MIT - http://datatables.net/license_mit
         */

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

// DB table to use
        $table = $_table;

// Table's primary key
        $primaryKey = $primary_Key;

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

        /*
        $columns = array(
            array('db' => 'reservation_id', 'dt' => 0),
            array('db' => 'created', 'dt' => 1),
            array('db' => 'qty', 'dt' => 2),
            // array( 'db' => 'checkin',       'dt' => 3 ),
            array(
                'db' => 'checkin',
                'dt' => 3,
                'formatter' => function ($d, $row) {
                    return date('Y-m-j', strtotime($d));

                }),
            // array( 'db' => 'checkout',      'dt' => 4 ),
            array(
                'db' => 'checkout',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return date('Y-m-j', strtotime($d));

                }),
            array('db' => 'client_name', 'dt' => 5),
            array('db' => 'status', 'dt' => 6),

        );
        */

// SQL server connection information
        $database = '';
        $host = '';
        $username = '';
        $password = '';

        $mysql = include realpath(APPLICATION_DIR . "Config/mysql_db_config.php");

        extract($mysql);

        $sql_details = array(
            'user' => $username,
            'pass' => $password,
            'db' => $database,
            'host' => $host
        );

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

//require( 'Ssp.php' );
        echo json_encode(Ssp::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $this->whereAll),
                JSON_UNESCAPED_UNICODE);
    }
}
