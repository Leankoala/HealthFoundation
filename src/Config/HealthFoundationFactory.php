<?php

namespace Leankoala\HealthFoundation\Config;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\HealthFoundation;
use PhmLabs\Components\Init\Init;

class HealthFoundationFactory
{
    public static function from($configArray)
    {
        $healthFoundation = new HealthFoundation();

        if (!array_key_exists('checks', $configArray)) {
            throw new \RuntimeException('The mandatory config element "checks" is missing.');
        }

        $healthFoundation = self::initChecks($configArray, $healthFoundation);

        return $healthFoundation;
    }

    /**
     * @param array $configArray
     *
     * @return HealthFoundation
     */
    private static function initChecks($configArray, HealthFoundation $healthFoundation)
    {
        foreach ($configArray['checks'] as $key => $checkArray) {

            /** @var Check $check */
            $check = Init::initialize($checkArray, 'check');

            if (array_key_exists('description', $checkArray)) {
                $description = $checkArray['description'];
            } else {
                $description = "";
            }

            if (array_key_exists('identifier', $checkArray)) {
                $identifier = $checkArray['identifier'];
            } else {
                $identifier = $key;
            }

            $healthFoundation->registerCheck($check, $identifier, $description);
        }

        return $healthFoundation;
    }
}
