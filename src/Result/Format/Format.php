<?php

namespace Leankoala\HealthFoundation\Result\Format;

use Leankoala\HealthFoundation\RunResult;

interface Format
{
    public function handle(RunResult $runResult);
}