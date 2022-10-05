<?php

namespace App\Controllers;

use Core\Controller;
use Exception;
use PDO;
use PDOException;
use Core\Libs\{Request, Response, Csrf, Validator};
use Core\Libs\Support\Facades\{Url, Crypt, DB, Log, Config, Validator as ValidatorFacade};

class BalanceController extends Controller
{
    /**
     * @var PDO
     */
    public $dbh;

    /**
     * BalanceController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->dbh = DB::pdo();
    }

    public function spend($userId, $amount): void
    {
        $this->dbh->query("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");

        try {

            $spendId = $this->startTransaction($userId, $userId, $amount, 'Spend');

            $this->dbh->beginTransaction();
            $balance = $this->getBalance($userId, false);
            echo "The user balance is: $balance";

            if ($balance < $amount) {
                die(" The account does not have enough funds ");

            }

            //  sleep(20);

            $sth = $this->dbh->prepare(
                "UPDATE `account` SET `balance` = balance - ?, `updated`=NOW() WHERE id=?;"
            );

            $sth->execute([$amount, $userId]);
            $this->dbh->commit();
            $this->updateTransaction($spendId);

        } catch (PDOException $e) {
            $this->dbh->rollBack();
            die($e->getMessage());

        }

        $balance = $this->getBalance($userId);
        echo " New user balance is: $balance";

    }

    private function startTransaction($fromId, $toId, $amount, $type)
    {
        try {
            $stm = $this->dbh->prepare(
                'INSERT INTO `transactions` (fromuser, touser, amount, transactiontype, start_at) VALUES (?,?,?,?, NOW())');

            $stm->execute([$fromId, $toId, $amount, $type]);
            return $this->dbh->lastInsertId();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    private function getBalance($userId, $forupdate = true)
    {
        $lock = ($forupdate === true) ? "FOR UPDATE" : "";
        $sth = $this->dbh->prepare("SELECT `balance` FROM `account` WHERE `id`=? {$lock}");

        $sth->execute([$userId]);

        $result = $sth->fetch(1);
        return $result->balance;
    }

    private function updateTransaction($transactionId)
    {
        try {
            $stm = $this->dbh->prepare(
                'UPDATE transactions SET updated=NOW() WHERE id=?');
            $stm->execute([$transactionId]);
            return $this->dbh->lastInsertId();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function transfer($fromUserId, $toUserId, $amount)
    {
        try {
            $sendId = $this->startTransaction($fromUserId, $toUserId, $amount, 'Send');
            $receiveId = $this->startTransaction($toUserId, $fromUserId, $amount, 'Receive');

            $this->dbh->beginTransaction();
            $fromUserbalance = $this->getBalance($fromUserId, false);
            $toUserIdBalance = $this->getBalance($toUserId);

            echo "The user $fromUserId balance is: $fromUserbalance <br>";
            echo "The user $toUserId balance is: $toUserIdBalance <br>";

            if ($fromUserbalance < $amount) {
                die(" The account does not have enough funds ");

            }

            $sth = $this->dbh->prepare(
                'UPDATE `account` SET `balance` = balance - ?, `updated`=NOW() WHERE id=?;'
            );

            //  ==== Sleep 30 sec============
            sleep(30);

            $sth->execute([$amount, $fromUserId]);

            // Receive amount
            $sth = $this->dbh->prepare(
                'UPDATE `account` SET `balance` = balance + ?, `updated`=NOW() WHERE id=?;'
            );
            $sth->execute([$amount, $toUserId]);
            $this->dbh->commit();
            $this->updateTransaction($sendId);
            $this->updateTransaction($receiveId);

        } catch (PDOException $e) {
            $this->dbh->rollBack();
            die($e->getMessage());
        }

        $fromUserbalance = $this->getBalance($fromUserId);
        $toUserIdBalance = $this->getBalance($toUserId);
        echo "New user $fromUserId balance is: $fromUserbalance <br>";
        echo "New user $toUserId balance is: $toUserIdBalance <br>";

    }

    public function balance($userId)
    {
        $balance = $this->getBalance($userId);
        echo "The user balance is: $balance";
    }

    public function trans()
    {
        try {
            sleep(1);
            $this->dbh->exec('CALL transfer(700,1,3,@a)');
            $a = $this->dbh->prepare('SELECT @a');
            $a->execute();
            $result = $a->fetch();
            dump($result[0]);
            if ($result[0] == 0) {
                die(" The account does not have enough funds ");
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }
}

// 289.16
//522.16
