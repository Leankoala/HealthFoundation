<?php

namespace Leankoala\HealthFoundation\Extenstion\Cache;

class Cache
{
    private $checkIdentifier;

    private $cacheDir = '/tmp/cache/';

    public function __construct($checkIdentifier)
    {
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        $this->checkIdentifier = $checkIdentifier;
    }

    public function set($key, $value)
    {
        file_put_contents($this->getFilename($key), $value);
    }

    public function get($key)
    {
        $file = $this->getFilename($key);

        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            return null;
        }
    }

    private function getGlobalKey($key)
    {
        return $this->checkIdentifier . '_' . $key;
    }

    private function getFilename($key)
    {
        $globalKey = $this->getGlobalKey($key);
        return $this->cacheDir . md5($globalKey) . '.cache';
    }
}
