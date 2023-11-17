<?php

namespace OpenAI;

class ChatCompletion
{
    public $client;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function create(array $options, bool $stream = false)
    {
        $response = $this->client->post("chat/completions", [
            "json" => $options,
            "stream" => $stream
        ]);

        if ($stream) {
            return $response;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
