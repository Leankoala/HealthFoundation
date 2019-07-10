<?php

namespace Leankoala\HealthFoundation\Filter;

use Leankoala\HealthFoundation\Check\Check;

interface Filter extends Check
{
    public function setCheck(Check $check);
}