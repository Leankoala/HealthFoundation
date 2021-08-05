<?php

namespace Leankoala\HealthFoundation\Check\Basic;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

/**
 * Class StaticCheck
 *
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-08-05
 */
class StaticCheck implements Check
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $status;

    public function __construct($identifier, $status, $message)
    {
        $this->identifier = $identifier;
        $this->status = $status;
        $this->message = $message;
    }

    public function run()
    {
        return new Result($this->status, $this->message);
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}
