<?php

namespace OpenAI;

use GuzzleHttp\Client;

class Embedding
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        $response = $this->client->post("embeddings", [
            'json' => $body
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
