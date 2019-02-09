<?php

namespace Leankoala\HealthFoundation\Check\Device;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class UptimeCheck implements Check
{
    const IDENTIFIER = 'base:device:uptime';

    /**
     * @var \DateInterval
     */
    private $dateInterval;

    public function init($maxUptime)
    {
        $this->dateInterval = \DateInterval::createFromDateString($maxUptime);
    }

    /**
     * Checks if the server uptime is ok
     *
     * @return Result
     */
    public function run()
    {
        $uptime = $this->getUptime();

        if ($this->dateIntervalToSeconds($uptime) > $this->dateIntervalToSeconds($this->dateInterval)) {
            return new Result(Result::STATUS_FAIL, 'Servers uptime is too high (' . $this->dateIntervalToString($uptime) . ')');
        } else {
            return new Result(Result::STATUS_PASS, 'Servers uptime is ok (' . $this->dateIntervalToString($uptime) . ')');
        }
    }

    /**
     * @return \DateInterval
     * @throws \Exception
     */
    private function getUptime()
    {
        $systemStartDate = new \DateTime(date('Y-m-d H:i:s', \uptime()));
        $now = new \DateTime();

        $uptime = $now->diff($systemStartDate);

        return $uptime;
    }

    private function dateIntervalToSeconds(\DateInterval $dateInterval)
    {
        $reference = new \DateTimeImmutable;
        $endTime = $reference->add($dateInterval);

        return $reference->getTimestamp() - $endTime->getTimestamp();
    }

    private function dateIntervalToString(\DateInterval $dateInterval)
    {
        $string = '';

        if ($dateInterval->y > 0) {
            $string .= $dateInterval->y . ' years ';
        }

        if ($dateInterval->d > 0) {
            $string .= $dateInterval->d . ' days ';
        }

        if ($dateInterval->h > 0) {
            $string .= $dateInterval->h . ' hours ';
        }

        if ($dateInterval->i > 0) {
            $string .= $dateInterval->i . ' minutes ';
        }

        return trim($string);
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
