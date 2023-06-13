<?php

namespace OpenAI;

class ChatCompletion
{
    public $client;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function create(array $options)
    {
        $response = $this->client->post("chat/completions", [
            "json" => $options
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
