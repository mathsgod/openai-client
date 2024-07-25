<?php

namespace OpenAI;

class ChatCompletions
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        return  $this->client->post("chat/completions", [
            "json" => $body,
        ]);
    }

    public function createAsync(array $options, bool $stream = false)
    {
        return $this->client->getHttpClient()->postAsync("chat/completions", [
            "json" => $options,
            "stream" => $stream
        ]);
    }
}
