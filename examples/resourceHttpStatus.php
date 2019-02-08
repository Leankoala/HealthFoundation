<?php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// http resource status check
$httpStatusCheck = new \Leankoala\HealthFoundation\Check\Resource\Http\StatusCodeCheck();
$httpStatusCheck->init('https://about.google/intl/com/',200); // PASS
// $httpStatusCheck->init('https://about.google/intl/com/',404); // WARN
// $httpStatusCheck->init('https://www.there-is-no-domain-connected-'.time().'.com/index.html',200); // FAIL
$foundation->registerCheck($httpStatusCheck);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Http resource status as expected', 'Http resource status is different or got errors');
