<?php

namespace Leankoala\HealthFoundation\Result\Format\Koality;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\Result\Format\Format;
use Leankoala\HealthFoundation\RunResult;

class KoalityFormat implements Format
{
    const DEFAULT_OUTPUT_PASS = 'The health check was passed.';
    const DEFAULT_OUTPUT_WARN = 'Warning.';
    const DEFAULT_OUTPUT_FAIL = 'The health check failed.';

    private $passMessage = self::DEFAULT_OUTPUT_PASS;
    private $failMessage = self::DEFAULT_OUTPUT_FAIL;

    public function __construct($passMessage = null, $failMessage = null)
    {
        if ($passMessage) {
            $this->passMessage = $passMessage;
        }

        if ($failMessage) {
            $this->failMessage = $failMessage;
        }
    }

    public function handle(RunResult $runResult)
    {
        header('Content-Type: application/json');

        $output = $this->getOutput($runResult, $this->passMessage, $this->failMessage);

        $details = [];

        foreach ($runResult->getResults() as $resultArray) {
            /** @var Result $result */
            $result = $resultArray['result'];

            /** @var Check $check */
            $check = $resultArray['check'];


            if (is_string($resultArray['identifier'])) {
                $identifier = $resultArray['identifier'];
            } else {
                $identifier = $check->getIdentifier();
            }

            $details[$identifier] = [
                'status' => $result->getStatus(),
                'output' => $result->getMessage()
            ];

            $description = $resultArray['description'];
            if ($description) {
                $details[$identifier]['description'] = $description;
            }

            if ($result instanceof MetricAwareResult) {
                $details[$identifier]["observedValue"] = $result->getMetricValue();
                $details[$identifier]["observedUnit"] = $result->getMetricUnit();

                if ($result->getMetricType()) {
                    $details[$identifier]['metricType'] = $result->getMetricType();
                }

                if (!is_null($result->getObservedValuePrecision())) {
                    $details[$identifier]['observedValuePrecision'] = $result->getObservedValuePrecision();
                }

                if (is_numeric($result->getLimit())) {
                    $details[$identifier]['limit'] = $result->getLimit();
                }

                if (!is_null($result->getLimitType())) {
                    $details[$identifier]['limitType'] = $result->getLimitType();
                }
            } else {
                if ($result->getStatus() == Result::STATUS_PASS) {
                    $details[$identifier]["observedValue"] = 1;
                } else {
                    $details[$identifier]["observedValue"] = 0;
                }
                $details[$identifier]["metricType"] = MetricAwareResult::METRIC_TYPE_PERCENT;
                $details[$identifier]["observedUnit"] = 'percent';
            }

            $attributes = $result->getAttributes();
            if (count($attributes) > 0) {
                $details['attributes'] = $attributes;
            }
        }

        $resultArray = [
            'status' => $runResult->getStatus(),
            'output' => $output,
            'checks' => $details
        ];

        echo json_encode($resultArray, JSON_PRETTY_PRINT);
    }

    private function getOutput(RunResult $runResult, $passMessage = null, $failMessage = null)
    {
        if ($runResult->getStatus() == Result::STATUS_PASS) {
            if (is_null($passMessage)) {
                $output = self::DEFAULT_OUTPUT_PASS;
            } else {
                $output = $passMessage;
            }
        } else {
            if (is_null($failMessage)) {
                $output = self::DEFAULT_OUTPUT_FAIL;
            } else {
                $output = $failMessage;
            }
        }

        return $output;
    }
}
