<?php

include_once __DIR__ . '/../../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// max disc usage 95%
$spaceUsedCheck = new \Leankoala\HealthFoundation\Check\Device\SpaceUsedCheck();
$spaceUsedCheck->init(95);

$foundation->registerCheck($spaceUsedCheck, 'space_used_check', '\Space used on storage server');

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Storage server is up and running.', 'Some problems occurred on storage server.');
