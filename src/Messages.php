<?php

namespace OpenAI;

class Messages
{

    private $client;
    private $thread_id;

    public function __construct(Client $client, string $thread_id)
    {
        $this->client = $client;
        $this->thread_id = $thread_id;
    }

    public function create(array $body)
    {
        return $this->client->post("threads/" . $this->thread_id . "/messages", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
            "json" => $body
        ]);
    }

    public function list()
    {
        return $this->client->get("threads/" . $this->thread_id . "/messages", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
