<?php

namespace Leankoala\HealthFoundation\Check\Frameworks\Symfony;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class DatabaseConnected
 *
 * @package Leankoala\HealthFoundation\Check\Frameworks\Symfony
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-03-02
 */
class DatabaseConnected implements Check
{
    const IDENTIFIER = 'base:frameworks:symfony:databaseConnected';

    /**
     * @var Registry
     */
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function run()
    {
        /** @var Connection $connection */
        $connection = $this->doctrine->getConnection();

        if ($connection->isConnected()) {
            $result = new Result(Result::STATUS_PASS, 'Doctrine is connected to the database.');
        } else {
            $result = new Result(Result::STATUS_FAIL, 'Doctrine is not connected to the database.');
        }

        return $result;
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
