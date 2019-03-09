<?php

include_once __DIR__ . '/../../../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

$toggleCheck = new \Leankoala\HealthFoundation\test\Check\ToggleStatusCheck();

$foundation->registerCheck($toggleCheck, null, 'Test memory by toggling the check pass/fail.');

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat(
    'pass',
    'fail'
);

$formatter->handle($runResult);
