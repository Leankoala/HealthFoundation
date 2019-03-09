<?php

namespace Leankoala\HealthFoundation\Decorator;

use Leankoala\HealthFoundation\Check\Check;

interface Decorator extends Check
{
    public function setCheck(Check $check);
}