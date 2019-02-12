<?php

namespace Leankoala\HealthFoundation;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class RunResult
{
    /**
     * @var Result[]
     */
    private $singleResults = [];

    private $globalStatus = Result::STATUS_PASS;

    public function addResult(Check $check, Result $result, $identifier = false, $description = "")
    {
        $this->singleResults[] = ['check' => $check, 'result' => $result, 'identifier' => $identifier, 'description' => $description];

        $this->globalStatus = Result::getHigherWeightedStatus($this->globalStatus, $result->getStatus());
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->singleResults;
    }

    public function getStatus()
    {
        return $this->globalStatus;
    }
}
