<?php

namespace Leankoala\HealthFoundation\Check\Device;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

class SpaceUsedCheck implements Check
{
    const IDENTIFIER = 'base:device:spaceUsed';

    private $maxUsageInPercent = 95;

    public function init($maxUsageInPercent)
    {
        $this->maxUsageInPercent = $maxUsageInPercent;
    }

    /**
     * Checks if the space left on device is sufficient
     *
     * @return Result
     */
    public function run()
    {
        $free = disk_free_space('/');
        $total = disk_total_space('/');

        $usage = 100 - round(($free / $total) * 100);

        if ($usage > $this->maxUsageInPercent) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'No space left on device. ' . $usage . '% used.');
        } else {
            $result = new MetricAwareResult(Result::STATUS_PASS, 'Enough space left on device. ' . $usage . '% used.');
        }

        $result->setMetric($usage, 'percent');

        return $result;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
