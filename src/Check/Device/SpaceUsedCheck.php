<?php

namespace Leankoala\HealthFoundation\Check\Device;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

class SpaceUsedCheck implements Check
{
    const IDENTIFIER = 'base:device:spaceUsed';

    private $maxUsageInPercent = 95;

    private $directory = '/';

    public function init($maxUsageInPercent, $directory = '/')
    {
        $this->maxUsageInPercent = $maxUsageInPercent;
        $this->directory = $directory;
    }

    /**
     * Checks if the space left on device is sufficient
     *
     * @return Result
     */
    public function run()
    {
        $free = disk_free_space($this->directory);
        $total = disk_total_space($this->directory);

        $usage = 100 - round(($free / $total) * 100);

        if ($usage > $this->maxUsageInPercent) {
            $result = new MetricAwareResult(Result::STATUS_FAIL, 'No space left on device. ' . $usage . '% used (' . $this->directory . ').');
        } else {
            $result = new MetricAwareResult(Result::STATUS_PASS, 'Enough space left on device. ' . $usage . '% used (' . $this->directory . ').');
        }

        $result->setMetric($usage, 'percent');
        $result->setLimit($this->maxUsageInPercent);
        $result->setLimitType(MetricAwareResult::LIMIT_TYPE_MAX);

        return $result;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
