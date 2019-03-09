<?php

namespace Leankoala\HealthFoundation\Check;

use Leankoala\HealthFoundation\Extenstion\Cache\Cache;

interface CacheAwareCheck extends Check
{
    public function setCache(Cache $cache);
}
