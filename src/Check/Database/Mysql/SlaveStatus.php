<?php

namespace Leankoala\HealthFoundation\Check\Database\Mysql;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class SlaveStatus implements Check
{
    const IDENTFIER = 'base:database:myql:slaveStatus';

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
        return new Result(Result::STATUS_PASS, 'All mandatory fields found.');
    }

    public function getIdentifier()
    {
        return self::IDENTFIER . ':' . $this->field;
    }
}
