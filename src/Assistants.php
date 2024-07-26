<?php

namespace OpenAI;

class Assistants
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list()
    {
        return $this->client->get("assistants", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }


    public function create(array $body)
    {
        return $this->client->post("assistants", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
            "json" => $body
        ]);
    }

    public function delete(string $assistant_id)
    {
        return $this->client->delete("assistants/" . $assistant_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
