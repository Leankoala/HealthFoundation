<?php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// check uptime
$uptimeCheck = new \Leankoala\HealthFoundation\Check\Device\UptimeCheck();
$uptimeCheck->init('-1 days');

$foundation->registerCheck($uptimeCheck);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Http resource status as expected', 'Http resource status is different or got errors');
