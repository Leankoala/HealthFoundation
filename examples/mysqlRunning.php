<?php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// check if mysql database is running
$slaveCheckRunning = new \Leankoala\HealthFoundation\Check\Database\Mysql\MysqlRunningCheck();
$slaveCheckRunning->init('root', '122');

$foundation->registerCheck($slaveCheckRunning);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Mysql slave server is up and running.', 'Some problems occurred for mysql slave server.');
