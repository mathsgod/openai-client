<?php

namespace OpenAI;

class Moderations
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        return  $this->client->post("moderations", [
            "json" => $body,
        ]);
    }
}
