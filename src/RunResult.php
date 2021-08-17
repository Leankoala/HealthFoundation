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

    /**
     * @param Check $check
     * @param Result $result
     * @param false $identifier
     * @param string $description
     * @param string $group
     */
    public function addResult(Check $check, Result $result, $identifier = false, $description = "", $group = "")
    {
        $this->singleResults[] = [
            'check' => $check,
            'result' => $result,
            'identifier' => $identifier,
            'description' => $description,
            'group' => $group
        ];

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
