<?php

namespace OpenAI;

use Exception;

class Threads
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        return $this->client->post("threads", [
            "json" => $body
        ]);
    }


    public function list()
    {
        return $this->client->get("threads", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
