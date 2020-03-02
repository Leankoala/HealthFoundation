<?php

namespace Leankoala\HealthFoundation\Check\Files\Content;

use Leankoala\HealthFoundation\Check\BasicCheck;
use Leankoala\HealthFoundation\Check\MetricAwareResult;
use Leankoala\HealthFoundation\Check\Result;

class NumberOfLinesCheck extends BasicCheck
{
    protected $identifier = 'base:files:content:numberOfLines';

    private $file;

    private $relation;

    private $limit;

    const RELATION_MAX = 'max';
    const RELATION_MIN = 'min';

    private $pattern;

    /**
     * @return MetricAwareResult
     */
    public function run()
    {
        if (!file_exists($this->file)) {
            return new MetricAwareResult(Result::STATUS_FAIL, 'Unable to get document length because file does not exist.');
        }

        $numberLines = $this->getNumberOfLines();

        return $this->processData($numberLines);
    }

    /**
     * @return int
     */
    private function getNumberOfLines()
    {
        $grep = '';
        if ($this->pattern) {
            foreach ($this->pattern as $pattern) {
                $grep .= ' | grep -a  "' . $pattern . '"';
            }
        }

        $command = 'cat ' . $this->file . $grep . ' | wc -l';

        exec($command, $output, $return);

        return (int)$output[0];
    }

    /**
     * @param $numberLines
     * @return Result
     */
    private function processData($numberLines)
    {
        if ($this->relation === self::RELATION_MAX) {
            if ($numberLines > $this->limit) {
                $result = new MetricAwareResult(Result::STATUS_FAIL, 'The document contains too many lines (' . $numberLines . '). Expected where ' . $this->limit . ' at the most.');
            } else {
                $result = new MetricAwareResult(Result::STATUS_PASS, 'The document contains ' . $numberLines . ' lines. Expected where ' . $this->limit . ' at the most.');
            }
        } else {
            if ($numberLines < $this->limit) {
                $result = new MetricAwareResult(Result::STATUS_FAIL, 'The document contains too few lines (' . $numberLines . '). Expected where ' . $this->limit . ' at least.');
            } else {
                $result = new MetricAwareResult(Result::STATUS_PASS, 'The document contains ' . $numberLines . ' lines. Expected where ' . $this->limit . ' at least.');
            }
        }

        $result->setMetric($numberLines, 'lines');

        return $result;
    }

    public function init($file, $limit, $relation = self::RELATION_MAX, $pattern = null)
    {
        $this->file = $file;
        $this->pattern = (array)$pattern;
        $this->relation = $relation;
        $this->limit = $limit;
    }

    protected function getCheckIdentifier()
    {
        return $this->identifier . '.' . md5($this->file . serialize($this->pattern));
    }
}
