<?php

namespace Leankoala\HealthFoundation;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class HealthFoundation
{
    /**
     * @var Check[]
     */
    private $registeredChecks = [];

    public function registerCheck(Check $check, $failOnFail = true)
    {
        $this->registeredChecks[] = $check;
    }

    public function runHealthCheck()
    {
        $runResult = new RunResult();

        foreach ($this->registeredChecks as $check) {

            $checkResult = $check->run();
            $runResult->addResult($check, $checkResult);
        }

        return $runResult;
    }
}
