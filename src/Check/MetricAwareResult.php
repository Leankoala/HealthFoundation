<?php

namespace Leankoala\HealthFoundation\Check;

class MetricAwareResult extends Result
{
    /**
     * @var integer
     */
    private $metricValue;

    private $metricUnit;

    public function setMetric($value, $unit)
    {
        $this->metricValue = $value;
        $this->metricUnit = $unit;
    }

    /**
     * @return integer
     */
    public function getMetricValue()
    {
        return $this->metricValue;
    }

    /**
     * @return string
     */
    public function getMetricUnit()
    {
        return $this->metricUnit;
    }
}
