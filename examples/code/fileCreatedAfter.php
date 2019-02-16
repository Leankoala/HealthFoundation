<?php

include_once __DIR__ . '/../../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// max disc usage 95%
$fileCreatedAfterCheck = new \Leankoala\HealthFoundation\Check\Files\FileCreatedAfterCheck();
$fileCreatedAfterCheck->init('/leankoala/storage/file/03/72', new DateTime("-1 year"), '*.json');

$foundation->registerCheck($fileCreatedAfterCheck);

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
$formatter->handle($runResult, 'Backup file was created successfully.', 'Seems like the backup script does not create new archives.');
