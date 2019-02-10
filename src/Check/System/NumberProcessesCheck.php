<?php

namespace Leankoala\HealthFoundation\Check\System;

use Leankoala\HealthFoundation\Check\Check;
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
        $command = 'ps aux | grep ' . $this->processName . ' | wc -l';

        exec($command, $output);

        $count = (int)$output[0];

        if ($count > $this->maxNumber) {
            return new Result(Result::STATUS_FAIL, 'Too many processes found "' . $this->processName . '". Current: ' . $count . ' / expected < ' . $this->maxNumber . '.');
        }

        if ($count < $this->minNumber) {
            return new Result(Result::STATUS_FAIL, 'Too few processes found "' . $this->processName . '". Current: ' . $count . ' / expected > ' . $this->maxNumber . '.');
        }

        return new Result(Result::STATUS_PASS, 'Number of processes "' . $this->processName . '" was within limits. Current number is ' . $count . '.');
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER . ':' . md5($this->processName);
    }
}
