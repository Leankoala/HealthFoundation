<?php

namespace Leankoala\HealthFoundation\Check\Resource\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\HttpClientAwareCheck;
use Leankoala\HealthFoundation\Check\Result;

class StatusCodeCheck implements Check, HttpClientAwareCheck
{
    const IDENTIFIER = 'base:resource:http:status';

    private $destination = null;
    private $expectedHttpStatus = null;

    /**
     * @var Client
     */
    private $httpClient;

    public function init($destination, $expectedHttpStatus = 200)
    {
        $this->destination = $destination;
        $this->expectedHttpStatus = (int)$expectedHttpStatus;
    }

    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Checks the response status
     *
     * @return Result
     */
    public function run()
    {
        try {
            $response = $this->httpClient->request('HEAD', $this->destination);
            $statusCode = $response->getStatusCode();
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
        } catch (\Exception $e) {
            $statusCode = 0;
            $errorMessage = $e->getMessage();
        }

        if ($statusCode === $this->expectedHttpStatus) {
            return new Result(Result::STATUS_PASS, 'HTTP status code for ' . $this->destination . ' was ' . $statusCode . ' as expected.');
        } else {
            if ($statusCode === 0) {
                return new Result(Result::STATUS_FAIL, 'The HTTP request could not be sent. Message: ' . $errorMessage);
            } else {
                return new Result(Result::STATUS_FAIL, 'HTTP status for ' . $this->destination . ' is different [' . $this->expectedHttpStatus . ' was expected but ' . $statusCode . ' was delivered]');
            }
        }
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
