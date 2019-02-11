<?php

namespace Leankoala\HealthFoundation\Check;

abstract class BasicCheck implements Check
{
    protected $identifier;

    protected $customIdentfier;

    /**
     * @param mixed $customIdentifier
     */
    public function setIdentifier($customIdentfier)
    {
        $this->customIdentfier = $customIdentfier;
    }

    protected function getCheckIdentifier()
    {
        return $this->identifier;
    }

    final public function getIdentifier()
    {
        if ($this->customIdentfier) {
            return $this->customIdentfier;
        } else {
            return $this->getCheckIdentifier();
        }
    }
}
