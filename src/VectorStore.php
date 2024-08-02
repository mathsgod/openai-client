<?php

namespace OpenAI;

class VectorStore
{
    private $client;
    private $vector_store_id;

    public function __construct(Client $client, string $vector_store_id)
    {
        $this->client = $client;
        $this->vector_store_id = $vector_store_id;
    }

    public function delete()
    {
        return $this->client->delete("vector_stores/" . $this->vector_store_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function files()
    {
    }
}
