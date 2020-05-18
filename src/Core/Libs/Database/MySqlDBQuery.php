<?php

namespace Core\Libs\Database;

use Core\Libs\Support\Facades\Config;
use Core\Libs\Pagination;
use Core\Libs\Support\Facades\Log;
use Core\Libs\Support\Facades\Url;
use PDOException;

trait MySqlDBQuery
{
    /**
     * @var \PDO
     */
    public $dbh;
//-------------------------
    /**
     * @var
     */
    public $table;
    /**
     * @var
     */
    public $field_name;
    /**
     * @var
     */
    public $wheres;
    /**
     * @var array
     */
    public $or_wheres;

    /**
     * @var array
     */
    public $bind_params;
    /**
     * @var
     */
    public $orderBy;

    /**
     * @var
     */
    public $limit;
    /**
     * @var
     */
    public $groupBy;

    public $logger;


    public function connection(\PDO $connection = null)
    {
        $this->dbh = $connection;

        // $this->dbh = MySqlPDOConnection::getInstance()->getConnection();
    }

    /**
     * Executing raw sql query
     * @param $sql
     * @param null $args
     * @return \PDOStatement
     * @throws \Exception
     */
    public function execute_sql($sql, $args = NULL)
    {
        try {
            $stmt = $this->dbh->prepare($sql);

            $stmt->execute($args);

        } catch (PDOException $e) {
            if (Config::getConfigFromFile("environment") !== "production") {
                echo $e->getCode() . ' <br>' . $e->getMessage() . '<br>' . $e->getTraceAsString();

            } else {
                $log = 'DB Error:' . $e->getCode() . ' ' . $e->getMessage();
                Log::channel('database', 'db_errors.html', \Monolog\Formatter\HtmlFormatter::class)
                    ->error($log, [$e->getTraceAsString()]);
                die('You do not do this! Possible database problem!');
            }
        }

        $this->_reset();

        return $stmt;
    }

    /**
     * $this->where([
     *   ['id','!=','20'],
     *   ['name','!=','plamen'],
     *   ['email','=','mail@mail.com']
     *  ]);
     * or
     * ->where('name', ' LIKE ', 'Jhon%')
     * @param $field
     * @return $this
     */
    public function where($field, $operator = '', $data = '')
    {

        if (is_array($field) === true) {

            foreach ($field as $k => $v) {
                list($fname, $op, $data) = $v;

                $this->wheres .= $fname . $op . ' ? ' . 'AND ';
                $this->bind_params[] = $data;
            }
            $this->wheres = rtrim($this->wheres, 'AND ');

        } else {
            $this->wheres = $field . $operator . ' ? ';
            $this->bind_params[] = $data;

        }

        return $this;
    }

    /**
     * @param $field
     * @param string $operator
     * @param string $data
     * @return $this
     * @throws \Exception
     */
    public function or_where($field, $operator = '', $data = '')
    {
        if (!isset($this->wheres)) {
            throw new \Exception('Не може да създаде sql заявка без where клауза');
        }
        if (is_array($field) === true) {

            foreach ($field as $k => $v) {
                list($fname, $op, $data) = $v;

                $this->or_wheres .= ' OR ' . $fname . $op . ' ? ' . ' OR ';
                $this->bind_params[] = $data;
            }
            $this->or_wheres = rtrim($this->or_wheres, ' OR ');

        } else {
            $this->or_wheres = ' OR ' . $field . $operator . ' ? ';
            $this->bind_params[] = $data;
        }
        return $this;
    }

    private function _wheres()
    {
        $where = '';

        if (isset($this->wheres)) {

            $where = " WHERE " . $this->wheres;

            if (isset($this->or_wheres)) {
                $where .= $this->or_wheres;
            }
        }
        return $where;
    }

    /**
     * @param $field
     * @param string $order
     * @return $this
     */
    public function orderBy($field, $order = 'ASC')
    {
        $this->orderBy = " ORDER BY " . $field . " " . strtoupper($order);

        return $this;
    }

    /**
     * @param $rows
     * @param int $offset
     * @return $this
     */
    public function limit($rows, $offset = 0)
    {
        //return only 10 records, start on record 16
        //$sql = "SELECT * FROM Orders LIMIT 15, 10";

        $this->limit = " LIMIT " . $rows . ' OFFSET ' . $offset;

        return $this;
    }

    public function rawLimit($str)
    {
        $this->limit = $str;
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function groupBy($field)
    {
        $this->groupBy = " GROUP BY " . $field . ' ';

        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     *  Select column of table
     * 'SELECT name, email FROM users'
     * @param mixed ...$field_name
     * @return $this
     */
    public function field(...$field_name)
    {
        $_field_name = '';
        foreach ($field_name as $field) {
            $_field_name .= ' ' . $field . ', ';
        }
        $this->field_name = rtrim($_field_name, ', ');

        return $this;
    }

    /**
     * @param mixed ...$field_name
     * @return MySqlDBQuery
     */
    public function select(...$field_name)
    {
        return $this->field(...$field_name);
    }

    /**
     * @return string
     */
    protected function _select()
    {
        $field = empty($this->field_name) ? "*" : $this->field_name;
        $sql = "SELECT " . $field . " FROM " . $this->table . $this->_wheres() . $this->groupBy . $this->orderBy . $this->limit;

        return trim($sql);
    }

    /**
     * get All row from table
     * @param null $fetch_style
     * @return array
     * @throws \Exception
     */
    public function get($fetch_style = \PDO::FETCH_OBJ)
    {
        $stmt = $this->execute_sql($this->_select(), $this->bind_params);

        return $stmt->fetchAll($fetch_style);
    }

    /**
     * Get all records as generator
     * @param int $fetch_style
     * @return \Generator
     * @throws \Exception
     */
    public function yield($fetch_style = \PDO::FETCH_OBJ)
    {
        $stmt = $this->execute_sql($this->_select(), $this->bind_params);

        while ($result = $stmt->fetch($fetch_style)) {
            yield $result;

        }

    }

    /**
     * @param null $fetch_style
     * @return mixed
     * @throws \Exception
     */
    public function getone($fetch_style = \PDO::FETCH_OBJ)
    {
        $stmt = $this->execute_sql($this->_select(), $this->bind_params);

        return $stmt->fetch($fetch_style);
    }

    /**
     *  $this->table('table')->count();
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . $this->_wheres() . $this->groupBy;

        return $this->execute_sql($sql, $this->bind_params)->fetchColumn();
    }

    /**
     *
     * $data = ['email'=>'mail@m.com', 'val'=>0];
     * or
     * $data = [
     *    ['email'=>'mail@m.com', ''val'=>0],
     *    ['email'=>'mail-1@m.com', ''val'=>1]
     * ]
     *
     * $this->table('table')->insert($data)
     *
     * връща като масив - rowCount и lastInsertId
     *
     * @param $datas
     * @return array
     *
     */
    public function insert($datas)
    {
        /*
         * $data = ['field'=>'value', 'field1'=>'value1']
         * INSERT INTO table (field, field1) VALUES (?, ?)
         */

        // проверка дали е многомерен масива
        if (count($datas) === count($datas, COUNT_RECURSIVE)) {
            $normal_array[] = $datas;
        } else {
            $normal_array = $datas;
        }

        $affectedRows = 0;

        foreach ($normal_array as $data) {

            $field = implode(', ', array_keys($data));

            $values = rtrim(str_repeat("?, ", count(array_values($data))), ', ');

            try {
                $sql = "INSERT INTO " . $this->table . "(" . $field . ")" . " VALUES " . "(" . $values . ")";
                $stmt = $this->dbh->prepare($sql);
                $stmt->execute(array_values($data));
                $affectedRows += $stmt->rowCount();

            } catch (\PDOException $e) {

                echo $e->getCode() . "<br>" . $e->getMessage() . "<br>" . $e->getTraceAsString();
            }
        }

        $rows['rowCount'] = $affectedRows;
        $rows['lastInsertId'] = $this->dbh->lastInsertId();

        return $rows;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function delete()
    {
        $sql = "DELETE FROM " . $this->table . $this->_wheres();

        try {
            $res = $this->execute_sql($sql, $this->bind_params)->rowCount();

        } catch (\PDOException $e) {
            die ("Error when Delete table" . $e->getMessage());
        }

        return $res;
    }

    /**
     * $data = ['name'>'john', 'lastname'=>'doe']
     * $this->table('table-name')->where('id', '=', 1)->update($data);
     * @param $datas
     * @return string
     * @throws \Exception
     */
    public function update($datas)
    {
        // $sql = "UPDATE table SET name=?, lastname=? WHERE id=?";
        $set_data = "";

        foreach ($datas as $key => $val) {

            $set_data .= $key . "= ?, ";
        }

        $exec_val = array_merge(array_values($datas), $this->bind_params);
        $sql = "UPDATE " . $this->table . " SET " . rtrim($set_data, ', ') . $this->_wheres();

        try {
            //  $stmt = $this->dbh->prepare($sql);

            //  $stmt->execute($exec_val);
            $res = $this->execute_sql($sql, $exec_val)->rowCount();

        } catch (\PDOException $e) {
            die('Error when Update table ' . $this->table . ' ' . $e->getMessage());
        }

        return $res;
    }

    public function paginate($n)
    {
        $count_sql = "SELECT COUNT(*) AS count FROM {$this->table}" . $this->_wheres() . $this->groupBy;
        $sth = $this->dbh->prepare($count_sql);
        $sth->execute($this->bind_params);
        $count = (int)$sth->fetch(\PDO::FETCH_ASSOC)['count'];

        $pagination = new Pagination();

        $pagination->total($count);
        $pagination->url_pattern(Url::requestUrlPath() . '?page=(:num)');

        $link = $pagination->paginate($n);

        $this->rawLimit($pagination->limit);

        $paginator = new \stdClass();
        $paginator->link = $link;
        $paginator->data = $this->get();
        return $paginator;
    }

    /**
     * @return \PDO
     */
    public function pdo()
    {
        return $this->dbh;
    }

    /**
     * Reset all
     */
    protected function _reset()
    {
        unset($this->bind_params, $this->wheres, $this->or_wheres, $this->orderBy,
              $this->limit, $this->groupBy, $this->field_name);
    }

}
