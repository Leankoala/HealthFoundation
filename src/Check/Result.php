<?php

namespace Leankoala\HealthFoundation\Check;

class Result
{
    /**
     * Healthy
     */
    const STATUS_PASS = 'pass';

    /**
     * Healthy, with some concerns
     */
    const STATUS_WARN = 'warn';

    /**
     * Unhealthy
     */
    const STATUS_FAIL = 'fail';

    private static $statuses = [
        self::STATUS_PASS => 0,
        self::STATUS_WARN => 50,
        self::STATUS_FAIL => 100
    ];

    const LIMIT_TYPE_MIN = 'min';
    const LIMIT_TYPE_MAX = 'max';

    const KOALITY_IDENTIFIER_ORDERS_TOO_FEW = 'orders.too_few';
    const KOALITY_IDENTIFIER_PLUGINS_UPDATABLE = 'plugins.updatable';
    const KOALITY_IDENTIFIER_PRODUCTS_COUNT = 'products.active';

    const KOALITY_IDENTIFIER_SYSTEM_INSECURE = 'system.insecure';
    const KOALITY_IDENTIFIER_SECURITY_USERS_ADMIN_COUNT = 'security.user.admin.count';

    const KOALITY_IDENTIFIER_SERVER_DICS_SPACE_USED = 'server.space.used';

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $attributes = [];

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Add a new attribute to the result.
     *
     * @param string $key
     * @param mixed $value
     */
    public function addAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Return a list of attribute
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
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
