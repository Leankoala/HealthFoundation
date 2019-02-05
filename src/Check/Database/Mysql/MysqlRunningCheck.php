<?php

namespace Leankoala\HealthFoundation\Check\Database\Mysql;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class MysqlRunningCheck
 *
 * Checks if a mysql database is running on a defined host with user and password.
 *
 * @package Leankoala\HealthFoundation\Check\Database\Mysql
 */
class MysqlRunningCheck implements Check
{
    const IDENTIFIER = 'base:database:mysql:running';

    private $username;
    private $password;
    private $host;

    /**
     * @param string $username the mysql user
     * @param string $password the mysql password for the given user
     * @param string $host the mysql host
     */
    public function init($username, $password, $host = 'localhost')
    {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
    }

    public function run()
    {
        $mysqli = @(new \mysqli($this->host, $this->username, $this->password));

        if ($mysqli->connect_errno) {
            $message = 'Database is not running; ' . $mysqli->connect_error;
            return new Result(Result::STATUS_FAIL, $message);
        } else {
            return new Result(Result::STATUS_PASS, 'Mysql server is up and running.');
        }
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
