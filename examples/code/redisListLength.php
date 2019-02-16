<?php

include_once __DIR__ . '/../../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// check redis list length
$redisListLengthCheck = new \Leankoala\HealthFoundation\Check\Database\Redis\ListLengthCheck();
$redisListLengthCheck->init('koalamon', 1000);

$foundation->registerCheck($redisListLengthCheck);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Redis server is up and running.', 'Some problems occurred for redis server.');
