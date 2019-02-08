<?php

namespace Leankoala\HealthFoundation\Check;

use GuzzleHttp\Client;

interface HttpClientAwareCheck
{
    public function setHttpClient(Client $client);
}