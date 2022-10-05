<?php


namespace Core\Libs\Session;

use Core\Libs\Support\Facades\Config;

class DataBaseSessionHandler implements \SessionHandlerInterface
{
    private $link;

    public function open($savePath, $sessionName)
    {
        $driver = 'mysql';
        $database = '';
        $host = '';
        $username = '';
        $password = '';

        $mysql = include realpath(APPLICATION_DIR . "Config/mysql_db_config.php");

        extract($mysql);

        $link = mysqli_connect($host, $username, $password, $database);
        if ($link) {
            $this->link = $link;
            return true;
        } else {
            return false;
        }
    }

    public function close()
    {
        mysqli_close($this->link);
        return true;
    }

    public function read($id)
    {
        $result = mysqli_query($this->link, "SELECT Session_Data FROM tbl_session WHERE Session_Id = '" . $id . "' AND Session_Expires > '" . date('Y-m-d H:i:s') . "'");
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['Session_Data'];
        } else {
            return "";
        }
    }

    public function write($id, $data)
    {
        $DateTime = date('Y-m-d H:i:s');
        $NewDateTime = date('Y-m-d H:i:s', strtotime($DateTime) + Config::getConfigFromFile('session_cookie_lifetime'));

        //$NewDateTime = date('Y-m-d H:i:s',strtotime($DateTime.' + 1 hour'));
        $result = mysqli_query($this->link, "REPLACE INTO Session SET Session_Id = '" . $id . "', Session_Expires = '" . $NewDateTime . "', Session_Data = '" . $data . "'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        $result = mysqli_query($this->link, "DELETE FROM Session WHERE Session_Id ='" . $id . "'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function gc($maxlifetime)
    {
        $result = mysqli_query($this->link, "DELETE FROM Session WHERE ((UNIX_TIMESTAMP(Session_Expires) + " . $maxlifetime . ") < " . $maxlifetime . ")");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}

$handler = new \Core\Libs\Session\DataBaseSessionHandler();
session_set_save_handler($handler, true);
