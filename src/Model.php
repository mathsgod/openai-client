<?php

namespace OpenAI;

use GuzzleHttp\Client;

class Model
{
    public $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list()
    {
        $response = $this->client->get("models");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function retrieve(string $model)
    {
        $response = $this->client->get("models/$model");
        return json_decode($response->getBody()->getContents(), true);
    }
}
