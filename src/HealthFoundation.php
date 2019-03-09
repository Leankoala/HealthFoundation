<?php

namespace Leankoala\HealthFoundation;

use GuzzleHttp\Client;
use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\HttpClientAwareCheck;
use Leankoala\HealthFoundation\Check\CacheAwareCheck;
use Leankoala\HealthFoundation\Extenstion\Cache\Cache;

class HealthFoundation
{
    /**
     * @var Check[]
     */
    private $registeredChecks = [];

    /**
     * @var Client
     */
    private $httpClient;

    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Return the current httpClient if set otherwise create one.
     *
     * @return Client
     */
    private function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }


    /**
     * Return the current cache abstraction
     *
     * @return Cache
     */
    private function getCache(CacheAwareCheck $check)
    {
        return new Cache($check->getIdentifier());
    }

    public function registerCheck(Check $check, $identifier = false, $description = "")
    {
        if ($check instanceof HttpClientAwareCheck) {
            $check->setHttpClient($this->getHttpClient());
        }

        if ($check instanceof CacheAwareCheck) {
            $check->setCache($this->getCache($check));
        }

        if ($identifier) {
            $this->registeredChecks[$identifier] = ['check' => $check, 'description' => $description];
        } else {
            $this->registeredChecks[] = ['check' => $check, 'description' => $description];
        }
    }

    public function runHealthCheck()
    {
        $runResult = new RunResult();

        foreach ($this->registeredChecks as $identifier => $checkInfos) {
            /** @var Check $check */
            $check = $checkInfos['check'];

            $checkResult = $check->run();
            $runResult->addResult($check, $checkResult, $identifier, $checkInfos['description']);
        }

        return $runResult;
    }
}
