<?php

namespace OpenAI;

use GuzzleHttp\Client;

class Image
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        $response = $this->client->post("images/generations", [
            "json" => $body
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
