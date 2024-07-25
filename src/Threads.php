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
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
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

    public function retrieve(string $thread_id)
    {
        return $this->client->get("threads/" . $thread_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
