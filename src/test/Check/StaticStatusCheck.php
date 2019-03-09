<?php

namespace Leankoala\HealthFoundation\test\Check;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class StaticStatusCheck implements Check
{
    private $status = Result::STATUS_FAIL;
    private $message = 'Static status fail.';

    public function init($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function run()
    {
        return new Result($this->status, $this->message);
    }

    public function getIdentifier()
    {
        return 'test.check.staticStatus';
    }
}
