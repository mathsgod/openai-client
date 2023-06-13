<?php

namespace OpenAI;

use GuzzleHttp\Client;

class File
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list()
    {
        $response = $this->client->get("files");
        return json_decode($response->getBody()->getContents(), true);
    }
}
