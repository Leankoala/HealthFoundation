<?php

namespace Leankoala\HealthFoundation\Check\Docker\Container;


use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class ContainerIsRunningCheck implements Check
{
    const IDENTIFIER = 'base:docker:container:running';

    private $containerName;

    public function init($containerName)
    {
        $this->containerName = $containerName;
    }

    public function run()
    {
        $command = "docker inspect -f '{{.State.Running}}' " . $this->containerName . " 2>/dev/null ";

        exec($command, $output, $returnValue);

        $isRunning = ((bool)$output[0]);

        if ($isRunning) {
            return new Result(Result::STATUS_PASS, 'The container ' . $this->containerName . ' is running.');
        } else {
            return new Result(Result::STATUS_FAIL, 'The container ' . $this->containerName . ' is not running.');
        }
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER . '.' . $this->containerName;
    }
}
