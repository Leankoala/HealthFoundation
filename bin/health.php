#!/usr/bin/env php
<?php

define('APPLICATION_VERSION', '##development##');
define('APPLICATION_NAME', 'HealthFoundation');

define('COMPOSER_AUTOLOAD_FILE', __DIR__ . '/../vendor/autoload.php');
if (file_exists(COMPOSER_AUTOLOAD_FILE)) {
    include_once __DIR__ . '/../vendor/autoload.php';
} else {
    fwrite(STDERR,
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'wget http://getcomposer.org/composer.phar' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
    die(1);
}

$app = new \Leankoala\HealthFoundation\Cli\Application(APPLICATION_NAME, APPLICATION_VERSION);
$app->run();
