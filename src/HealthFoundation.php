<?php

namespace Leankoala\HealthFoundation;

use GuzzleHttp\Client;
use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\HttpClientAwareCheck;

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

    public function registerCheck(Check $check, $identifier = false)
    {
        if ($check instanceof HttpClientAwareCheck) {
            $check->setHttpClient($this->getHttpClient());
        }

        if ($identifier) {
            $this->registeredChecks[$identifier] = $check;
        } else {
            $this->registeredChecks[] = $check;
        }
    }

    public function runHealthCheck()
    {
        $runResult = new RunResult();

        foreach ($this->registeredChecks as $identifier => $check) {
            $checkResult = $check->run();
            $runResult->addResult($check, $checkResult, $identifier);
        }

        return $runResult;
    }
}
