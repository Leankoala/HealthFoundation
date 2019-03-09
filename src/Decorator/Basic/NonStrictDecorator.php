<?php

namespace Leankoala\HealthFoundation\Decorator\Basic;

use Leankoala\HealthFoundation\Check\CacheAwareCheck;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\Decorator\BasicDecorator;
use Leankoala\HealthFoundation\Extenstion\Cache\Cache;

class NonStrictDecorator extends BasicDecorator implements CacheAwareCheck
{
    private $maxErrorsInARow = 2;

    /**
     * @var Cache
     */
    private $cache;

    public function init($maxErrorsInARow = 2)
    {
        $this->maxErrorsInARow = $maxErrorsInARow;
    }

    public function run()
    {
        $result = $this->getCheck()->run();

        // @todo handle metric aware checks

        if ($result->getStatus() == Result::STATUS_FAIL) {
            $currentErrorsInARow = (int)$this->cache->get('errorsInARow');
            $currentErrorsInARow++;

            $this->cache->set('errorsInARow', $currentErrorsInARow);

            if ($currentErrorsInARow >= $this->maxErrorsInARow) {
                return $result;
            } else {
                if ($result instanceof MetricAwareResult) {
                    $metricResult = new MetricAwareResult(Result::STATUS_PASS, 'Passed because non-strict mode is activated. Failed with message: ' . $result->getMessage());
                    $metricResult->setMetric($result->getMetricValue(), $result->getMetricUnit());
                    return $metricResult;
                } else {
                    return new Result(Result::STATUS_PASS, 'Passed because non-strict mode is activated. Failed with message: ' . $result->getMessage());
                }
            }
        } else {
            $this->cache->set('errorsInARow', 0);
            return $result;
        }
    }

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }
}
