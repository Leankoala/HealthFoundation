<?php

namespace Leankoala\HealthFoundation\Check\Files\Content;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class NumberOfLinesCheck implements Check
{
    const IDENTIFIER = 'base:files:content:numberOfLines';

    private $file;

    private $relation;

    private $limit;

    const RELATION_MAX = 'max';
    const RELATION_MIN = 'min';

    private $pattern;

    public function run()
    {
        if (!file_exists($this->file)) {
            return new Result(Result::STATUS_FAIL, 'Unable to get document length because file does not exist.');
        }

        $grep = '';
        if ($this->pattern) {
            foreach ($this->pattern as $pattern) {
                $grep .= ' | grep "' . $pattern . '"';
            }
        }

        $command = 'cat ' . $this->file . $grep . ' | wc -l';
        
        exec($command, $output, $return);

        $numberLines = (int)$output[0];

        if ($this->relation === self::RELATION_MAX) {
            if ($numberLines > $this->limit) {
                return new Result(Result::STATUS_FAIL, 'The document contains too many lines (' . $numberLines . '). Expected where ' . $this->limit . ' at the most.');
            } else {
                return new Result(Result::STATUS_PASS, 'The document contains ' . $numberLines . ' lines. Expected where ' . $this->limit . ' at the most.');
            }
        } else {
            if ($numberLines < $this->limit) {
                return new Result(Result::STATUS_FAIL, 'The document contains too few lines (' . $numberLines . '). Expected where ' . $this->limit . ' at least.');
            } else {
                return new Result(Result::STATUS_PASS, 'The document contains ' . $numberLines . ' lines. Expected where ' . $this->limit . ' at least.');
            }
        }
    }

    public function init($file, $limit, $relation = self::RELATION_MAX, $pattern = null)
    {
        $this->file = $file;
        $this->pattern = (array)$pattern;
        $this->relation = $relation;
        $this->limit = $limit;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
