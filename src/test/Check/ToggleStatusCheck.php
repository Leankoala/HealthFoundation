<?php

namespace Leankoala\HealthFoundation\test\Check;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\CacheAwareCheck;
use Leankoala\HealthFoundation\Check\Result;
use Leankoala\HealthFoundation\Extenstion\Cache\Cache;

class ToggleStatusCheck implements Check, CacheAwareCheck
{
    /**
     * @var Cache
     */
    private $cache;

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function run()
    {
        $lastStatus = $this->cache->get('status');

        if ($lastStatus === Result::STATUS_FAIL) {
            $this->cache->set('status', Result::STATUS_PASS);
            return new Result(Result::STATUS_PASS, 'Toggle to pass');
        } else {
            $this->cache->set('status', Result::STATUS_FAIL);
            return new Result(Result::STATUS_FAIL, 'Toggle to fail');
        }
    }

    public function getIdentifier()
    {
        return 'test.check.toggleNumber';
    }
}
