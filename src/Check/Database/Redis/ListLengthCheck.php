<?php

namespace Leankoala\HealthFoundation\Check\Database\Redis;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class ListLength
 *
 * Check if the length of a redis list is not too big
 *
 * @package Leankoala\HealthFoundation\Check\Database\Redis
 */
class ListLengthCheck implements Check
{
    const IDENTFIER = 'base:database:redis:listLength';

    private $host;
    private $port;
    private $auth;
    private $listName;

    private $maxLength;

    public function init($listName, $maxLength, $auth = null, $host = 'localhost', $port = '6379')
    {
        $this->listName = $listName;
        $this->maxLength = $maxLength;

        $this->auth = $auth;
        $this->host = $host;
        $this->port = $port;
    }

    public function run()
    {
        $redis = new \Redis();
        $redis->connect($this->host);

        if ($this->auth) {
            $redis->auth($this->auth);
        }

        $length = $redis->lLen($this->listName);

        if ($length > $this->maxLength) {
            return new Result(Result::STATUS_FAIL, 'Too many elements in list ("' . $length . '"). Maximum was ' . $this->maxLength);
        } else {
            return new Result(Result::STATUS_FAIL, $length . ' elements in list. Maximum was ' . $this->maxLength);
        }
    }

    public function getIdentifier()
    {
        return self::IDENTFIER;
    }
}
