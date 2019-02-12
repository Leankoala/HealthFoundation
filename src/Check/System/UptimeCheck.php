<?php

namespace Leankoala\HealthFoundation\Check\System;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class UptimeCheck implements Check
{
    const IDENTIFIER = 'base:system:uptime';

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
        try {

            $uptime = $this->getUptime();
            if ($this->dateIntervalToSeconds($uptime) > $this->dateIntervalToSeconds($this->dateInterval)) {
                return new Result(Result::STATUS_FAIL, 'Servers uptime is too high (' . $this->dateIntervalToString($uptime) . ')');
            } else {
                return new Result(Result::STATUS_PASS, 'Servers uptime is ok (' . $this->dateIntervalToString($uptime) . ')');
            }

        } catch (\Exception $e) {
            return new Result(Result::STATUS_FAIL, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * @return \DateInterval
     * @throws \Exception
     */
    private function getUptime()
    {
        $uptime = \uptime();

        if ($uptime === false) {
            throw new \Exception('Uptime() cannot be calculated.');
        }

        $systemStartDate = new \DateTime(date('Y-m-d H:i:s', $uptime));
        $now = new \DateTime();

        $uptime = $systemStartDate->diff($now);

        return $uptime;
    }

    /**
     * @param \DateInterval $dateInterval
     * @return int
     * @throws \Exception
     */
    private function dateIntervalToSeconds(\DateInterval $dateInterval)
    {
        $reference = new \DateTimeImmutable;
        $endTime = $reference->add($dateInterval);

        return $endTime->getTimestamp() - $reference->getTimestamp();
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
