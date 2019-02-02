<?php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

$numberOfLinesCheck = new \Leankoala\HealthFoundation\Check\Files\Content\NumberOfLinesCheck();
$numberOfLinesCheck->init('/Users/nils.langner/Projekte/HealthFoundation/composer.lock', 15, \Leankoala\HealthFoundation\Check\Files\Content\NumberOfLinesCheck::RELATION_MIN);

$foundation->registerCheck($numberOfLinesCheck);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Backup file was created successfully.', 'Seems like the backup script does not create new archives.');
