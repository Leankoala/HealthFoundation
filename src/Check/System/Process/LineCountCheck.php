<?php

namespace Leankoala\HealthFoundation\Check\System\Process;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class LineCountCheck
 *
 * @package Leankoala\HealthFoundation\Check\System\Process
 *
 * @author Nils Langner <nils.langner@startwind.io>
 * created 2021-12-01
 */
class LineCountCheck implements Check
{
    private $command;
    private $maxNumber;
    private $minNumber;
    private $filters;

    public function init($command, $maxNumber = null, $minNumber = null, $filters = [])
    {
        $this->command = $command;
        $this->maxNumber = $maxNumber;
        $this->minNumber = $minNumber;
        $this->filters = $filters;
    }

    public function run()
    {
        $filterString = '';

        foreach ($this->filters as $filter) {
            $filterString .= '| grep -a "' . $filter . '"';
        }

        $command = $this->command . ' ' . $filterString . ' | wc -l';

        exec($command, $output);

        $count = (int)$output[0];

        if ($this->maxNumber && $count > $this->maxNumber) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'Too many lines found "' . $this->command . '" (current: ' . $count . ', expected < ' . $this->maxNumber . ').');
        } elseif ($this->minNumber && $count < $this->minNumber) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'Too few processes found "' . $this->command . '" (current: ' . $count . ' , expected >= ' . $this->minNumber . ').');
        } else {
            $result = new MetricAwareResult(Result::STATUS_PASS, 'Number of processes "' . $this->command . '" was within limits. Current number is ' . $count . '.');
        }

        $result->setMetric($count, 'process');
        $result->setLimit($this->maxNumber);
        $result->setLimitType(MetricAwareResult::LIMIT_TYPE_MAX);
        $result->setObservedValuePrecision(0);

        $result->addAttribute('command', $command);

        return $result;
    }

    public function getIdentifier()
    {
        return 'base:system:process:lineCount';
    }

}
