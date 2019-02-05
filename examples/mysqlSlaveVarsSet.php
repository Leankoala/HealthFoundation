<?php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// slave status check
$slaveCheckRunning = new \Leankoala\HealthFoundation\Check\Database\Mysql\Slave\SlaveStatusFieldCheck();
$slaveCheckRunning->init('Slave_IO_Running', 'Yes');

$foundation->registerCheck($slaveCheckRunning);

// slave status check
$slaveChecSqlkRunning = new \Leankoala\HealthFoundation\Check\Database\Mysql\Slave\SlaveStatusFieldCheck();
$slaveChecSqlkRunning->init('Slave_SQL_Running', 'Yes');

$foundation->registerCheck($slaveChecSqlkRunning);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Mysql slave server is up and running.', 'Some problems occurred for mysql slave server.');
