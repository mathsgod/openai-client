<?php

namespace OpenAI;

class Runs
{
    private $client;
    private $thread_id;


    public function __construct(Client $client, string $thread_id)
    {
        $this->client = $client;
        $this->thread_id = $thread_id;
    }

    public function list()
    {
        return $this->client->get("threads/$this->thread_id/runs", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
