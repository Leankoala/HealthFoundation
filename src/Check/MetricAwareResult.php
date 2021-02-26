<?php

namespace Leankoala\HealthFoundation\Check;

/**
 * Class MetricAwareResult
 *
 * @package Leankoala\HealthFoundation\Check
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-02-26
 */
class MetricAwareResult extends Result
{
    const METRIC_TYPE_NUMERIC = 'time_series_numeric';
    const METRIC_TYPE_PERCENT = 'time_series_percent';

    /**
     * @var integer
     */
    private $metricValue;

    private $metricUnit;

    private $metricType = self::METRIC_TYPE_NUMERIC;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var float|int
     */
    private $observedValue;

    /**
     * @var int
     */
    private $observedValuePrecision;

    /**
     * @var string
     */
    private $observedValueUnit;

    /**
     * @var string
     */
    private $limitType;

    /**
     * @var string
     */
    private $type;

    public function setMetric($value, $unit, $metricType = self::METRIC_TYPE_NUMERIC)
    {
        $this->metricValue = $value;
        $this->metricUnit = $unit;
        $this->metricType = $metricType;
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

    public function getMetricType()
    {
        return $this->metricType;
    }

    /**
     * Get the limit of the metric that was checked.
     *
     * This field is optional.
     *
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the limit of the metric that was checked.
     *
     * @param int $limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getLimitType()
    {
        return $this->limitType;
    }

    /**
     * @param string $limitType
     */
    public function setLimitType(string $limitType)
    {
        $this->limitType = $limitType;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getObservedValuePrecision()
    {
        return $this->observedValuePrecision;
    }

    /**
     * @param int $observedValuePrecision
     */
    public function setObservedValuePrecision($observedValuePrecision)
    {
        $this->observedValuePrecision = $observedValuePrecision;
    }

}
