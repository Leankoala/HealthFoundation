<?php


namespace Leankoala\HealthFoundation\Check\Basic\Fixed;


use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class FailureCheck implements Check
{
    const IDENTIFIER = 'base:basic:fixed:failure';

    public function run()
    {
        return new Result(Result::STATUS_FAIL, 'This fixed check always fails. It was created for debugging.');
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
