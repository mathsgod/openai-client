<?php

namespace OpenAI;

class VectorStoreFiles
{

    private $client;
    private $vector_store_id;

    public function __construct(Client $client, string $vector_store_id)
    {
        $this->client = $client;
        $this->vector_store_id = $vector_store_id;
    }

    public function create(array $body)
    {
        return $this->client->post("vector_stores/" . $this->vector_store_id . "/files", [
            "json" => $body,
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function list()
    {
        return $this->client->get("vector_stores/" . $this->vector_store_id . "/files", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function retrieve(string $file_id)
    {
        return (new VectorStoreFile($this->client, $this->vector_store_id, $file_id))->get();
    }
}
