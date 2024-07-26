<?php

namespace OpenAI;

class Assistant
{
    private $client;
    private $assistant_id;

    public function __construct(Client $client, string $assistant_id)
    {
        $this->client = $client;
        $this->assistant_id = $assistant_id;
    }

    public function modify(array $body)
    {
        return $this->client->post("assistants/" . $this->assistant_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
            "json" => $body
        ]);
    }

    public function delete()
    {
        return $this->client->delete("assistants/" . $this->assistant_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
