<?php

namespace Leankoala\HealthFoundation\Check\Files;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;


/**
 * Class FileExistsCheck
 *
 * This class checks if a given file exists
 *
 * @package Leankoala\HealthFoundation\Check\Files
 */
class FileExistsCheck implements Check
{
    const IDENTIFIER = 'base:files:fileExists';

    private $filename;

    public function run()
    {
        if (file_exists($this->filename)) {
            return new Result(Result::STATUS_PASS, 'The file "' . $this->filename . '" exists.');
        }else{
            return new Result(Result::STATUS_FAIL, 'The file "' . $this->filename . '" does not exist.');
        }
    }

    /**
     * @param $filename
     */
    public function init($filename)
    {
        $this->filename = $filename;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
