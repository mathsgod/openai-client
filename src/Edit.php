<?php

namespace OpenAI;

use GuzzleHttp\Client;

class Edit
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        $response = $this->client->post("edits", [
            'json' => $body
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
