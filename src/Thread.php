<?php

namespace OpenAI;

use Exception;
use GuzzleHttp\Client;

class Thread
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
