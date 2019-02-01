<?php

namespace Leankoala\HealthFoundation\Check\Database\Mysql;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class SlaveStatus implements Check
{
    const IDENTFIER = 'base:database:mysql:slaveStatus';

    private $userName;
    private $password;
    private $host;

    private $field;
    private $value;

    public function init($field, $value, $userName = 'root', $password = '', $host = 'localhost')
    {
        $this->host = $host;
        $this->userName = $userName;
        $this->password = $password;
        $this->field = $field;
        $this->value = $value;
    }

    public function run()
    {
        $mysqli = new \mysqli($this->host, $this->userName, $this->password);

        if ($mysqli->connect_errno) {
            return new Result(Result::STATUS_FAIL, sprintf("Connect failed: %s\n", $mysqli->connect_error));
        }

        $result = $mysqli->query("SHOW SLAVE STATUS");

        $fields = $result->fetch_assoc();

        if (is_array($fields)) {
            if (array_key_exists($this->field, $fields)) {
                if ($fields[$this->field] == $this->value) {
                    return new Result(Result::STATUS_PASS, 'Field "' . $this->field . '"" has value "' . $this->value . '" which was expected.');
                } else {
                    return new Result(Result::STATUS_FAIL, 'Field "' . $this->field . '"" has value ' . $fields[$this->field] . '. Expected was "' . $this->value . '"');
                }
            }
        }

        return new Result(Result::STATUS_FAIL, 'Field "' . $this->field . '" was not found in database slave status.');
    }

    public function getIdentifier()
    {
        return self::IDENTFIER . ':' . $this->field;
    }
}
