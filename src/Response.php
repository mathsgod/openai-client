<?php

namespace OpenAI;

use Evenement\EventEmitter;
use GuzzleHttp\Exception\GuzzleException;

class Response
{
    private $client;
    private $response_id;
    
    public function __construct(Client $client, string $response_id)
    {
        $this->client = $client;
        $this->response_id = $response_id;
    }

    public function getInputItems()
    {
        return $this->client->get("responses/{$this->response_id}/input_items");
    }
}
