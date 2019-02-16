<?php

namespace Leankoala\HealthFoundation\Config;

use Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat;
use PhmLabs\Components\Init\Init;

class FormatFactory
{
    /**
     * @param $configArray
     * @return IetfFormat
     */
    public static function from($configArray)
    {
        if (array_key_exists('format', $configArray)) {
            $format = Init::initialize($configArray['format']);
        } else {
            $format = new IetfFormat();
        }

        return $format;
    }
}
