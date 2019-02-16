<?php

include_once __DIR__ . '/../../vendor/autoload.php';

$configFile = __DIR__ . '/yaml/fileNumberOfLines.yml';

$configArray = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($configFile));

$healthFoundation = \Leankoala\HealthFoundation\Config\HealthFoundationFactory::from($configArray);
$format = \Leankoala\HealthFoundation\Config\FormatFactory::from($configArray);

$runResult = $healthFoundation->runHealthCheck();

$format->handle($runResult);