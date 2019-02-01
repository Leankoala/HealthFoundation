<?php

namespace Leankoala\HealthFoundation\Check\Files;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class FileCreatedAfterCheck implements Check
{
    const IDENTIFIER = 'base:files:newerThan';

    private $directory;

    private $pattern;

    /**
     * @var \DateTime
     */
    private $date;

    public function run()
    {
        $files = glob(rtrim($this->directory, '/') . DIRECTORY_SEPARATOR . $this->pattern);

        foreach ($files as $file) {
            if (is_file($file)) {
                if (filemtime($file) >= $this->date->getTimestamp()) {
                    return new Result(Result::STATUS_PASS, 'At least one file ("' . basename($file) . '", created: ' . date('Y-d-m H:i', filemtime($file)) . ') was found newer than ' . $this->date->format('Y-m-d H:m') . '.');
                }
            }
        }

        return new Result(Result::STATUS_FAIL, 'No file was found newer than ' . $this->date->format('Y-m-d H:m:s') . '.');
    }

    public function init($directory, \DateTime $date, $pattern = '*')
    {
        $this->directory = $directory;
        $this->date = $date;
        $this->pattern = $pattern;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
