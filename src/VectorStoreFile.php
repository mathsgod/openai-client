<?php

namespace OpenAI;

class VectorStoreFile
{
    private $client;
    private $vector_store_id;
    private $file_id;
    public function __construct(Client $client, string $vector_store_id, string $file_id)
    {
        $this->client = $client;
        $this->vector_store_id = $vector_store_id;
        $this->file_id = $file_id;
    }

    public function get()
    {
        return $this->client->get("vector_stores/" . $this->vector_store_id . "/files/" . $this->file_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function delete()
    {
        return $this->client->delete("vector_stores/" . $this->vector_store_id . "/files/" . $this->file_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
