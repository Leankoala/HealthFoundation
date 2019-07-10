<?php

namespace Leankoala\HealthFoundation\Filter\Basic;

use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\Filter\BasicFilter;

class TimeFilter extends BasicFilter
{
    private $startDateTime;

    private $endDateTime;

    public function init($month, $day, $hour, $minute, $interval)
    {
        throw new \RuntimeException('This filter is not implemented yet');

        $this->startDateTime = new \DateTime($startDateTime);
        $this->endDateTime = new \DateTime($endDateTime);
    }

    public function run()
    {
        $result = $this->getCheck()->run();

        // @todo handle metric aware checks

        if ($result->getStatus() == Result::STATUS_FAIL) {


        } else {
            return $result;
        }
    }
}
