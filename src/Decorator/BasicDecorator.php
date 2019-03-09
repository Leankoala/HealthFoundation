<?php

namespace Leankoala\HealthFoundation\Decorator;

use Leankoala\HealthFoundation\Check\Check;

abstract class BasicDecorator implements Decorator
{
    /**
     * @var Check
     */
    private $check;

    public function getIdentifier()
    {
        return $this->check->getIdentifier();
    }

    public function setCheck(Check $check)
    {
        $this->check = $check;
    }

    protected function getCheck()
    {
        return $this->check;
    }
}
