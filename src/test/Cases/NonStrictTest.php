<?php

include_once __DIR__ . '/../../../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

$staticCheck = new \Leankoala\HealthFoundation\test\Check\StaticStatusCheck();

$nonStrictStaticCheck = new \Leankoala\HealthFoundation\Decorator\Basic\NonStrictDecorator();
$nonStrictStaticCheck->setCheck($staticCheck);

$foundation->registerCheck($nonStrictStaticCheck, null, 'Test non-strict mode for static check.');

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat(
    'pass',
    'fail'
);

$formatter->handle($runResult);
