<?php

namespace OpenAI;

class VectorStores
{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        return $this->client->post("vector_stores", [
            "json" => $body,
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function list()
    {
        return $this->client->get("vector_stores", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
