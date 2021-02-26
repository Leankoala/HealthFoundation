<?php

namespace Leankoala\HealthFoundation\Check\System;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

class NumberProcessesCheck implements Check
{
    const IDENTIFIER = 'base:system:numberProcesses';

    private $processName;
    private $maxNumber;
    private $minNumber;

    public function init($processName, $maxNumber, $minNumber = 0)
    {
        $this->processName = $processName;
        $this->maxNumber = $maxNumber;
        $this->minNumber = $minNumber;
    }

    public function run()
    {
        $command = 'ps ax  | grep -a "' . $this->processName . '" | wc -l';

        exec($command, $output);

        $count = (int)$output[0] - 2;

        if ($count > $this->maxNumber) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'Too many processes found "' . $this->processName . '" (current: ' . $count . ', expected < ' . $this->maxNumber . ').');
        } elseif ($count < $this->minNumber) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'Too few processes found "' . $this->processName . '" (current: ' . $count . ' , expected > ' . $this->maxNumber . ').');
        } else {
            $result = new MetricAwareResult(Result::STATUS_PASS, 'Number of processes "' . $this->processName . '" was within limits. Current number is ' . $count . '.');
        }

        $result->setMetric($count, 'processes');
        $result->setLimit($this->maxNumber);
        $result->setLimitType(MetricAwareResult::LIMIT_TYPE_MAX);

        return $result;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER . ':' . md5($this->processName);
    }
}
