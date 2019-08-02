<?php

namespace Leankoala\HealthFoundation\Filter\Time;

use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\Filter\BasicFilter;

class DailyFilter extends BasicFilter
{
    private $hour;
    private $reason = false;

    public function init($hour, $reason = false)
    {
        $this->hour = $hour;
        $this->reason = $reason;
    }

    public function run()
    {
        $result = $this->getCheck()->run();

        $currentHour = (int)date('H');

        if ($result->getStatus() == Result::STATUS_FAIL) {
            if ($currentHour == $this->hour) {
                if ($this->reason) {
                    $message = $this->reason . ' (fail reason was: ' . $result->getMessage() . ')';
                } else {
                    $message = 'Passed due daily filter (fail reason was: ' . $result->getMessage() . ')';
                }

                if ($result instanceof MetricAwareResult) {
                    $newResult = new MetricAwareResult(Result::STATUS_PASS, $message);
                    $newResult->setMetric($result->getMetricValue(), $result->getMetricUnit());
                } else {
                    $newResult = new Result(Result::STATUS_PASS, $message);
                }

                return $newResult;
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }
}
