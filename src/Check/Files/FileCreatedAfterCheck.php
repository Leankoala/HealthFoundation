<?php

namespace Leankoala\HealthFoundation\Check\Files;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;


/**
 * Class FileCreatedAfterCheck
 *
 * This class checks if there is a file in a given directory that was created after a defined date.
 *
 * Use case 1: Is there a new backup file for a given database
 *
 * @package Leankoala\HealthFoundation\Check\Files
 */
class FileCreatedAfterCheck implements Check
{
    const IDENTIFIER = 'base:files:fileCreatedAfter';

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
                    return new Result(Result::STATUS_PASS, 'At least one file (name: ' . basename($file) . ', created: ' . date('Y-d-m H:i', filemtime($file)) . ') was created after ' . $this->date->format('Y-m-d H:m') . '.');
                }
            }
        }

        return new Result(Result::STATUS_FAIL, 'No file was found newer than ' . $this->date->format('Y-m-d H:m:s') . '.');
    }

    /**
     * @param string $directory the directory where to look for the file
     * @param \DateTime $date the date the file must be newer than
     * @param string $pattern if not all files should be analyzed there can be set a filter
     */
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
