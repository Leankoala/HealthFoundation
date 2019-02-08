<?php

namespace Leankoala\HealthFoundation\Check;

class Result
{
    /**
     * Healthy
     */
    const STATUS_PASS = 'pass';

    /**
     * healthy, with some concerns
     */
    const STATUS_WARN = 'warn';

    /**
     * unhealthy
     */
    const STATUS_FAIL = 'fail';

    private static $statuses = [
        self::STATUS_PASS => 0,
        self::STATUS_WARN => 50,
        self::STATUS_FAIL => 100
    ];

    private $status;

    private $message;

    /**
     * Result constructor.
     *
     * @param $status
     * @param $message
     */
    public function __construct($status, $message = "")
    {
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Returns the higher weighted status.
     *
     * @param $status1
     * @param $status2
     *
     * @return string
     */
    public static function getHigherWeightedStatus($status1, $status2)
    {
        if (self::$statuses[$status1] > self::$statuses[$status2]) {
            return $status1;
        } else {
            return $status2;
        }
    }
}
