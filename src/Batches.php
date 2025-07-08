<?php

namespace OpenAI;

class Batches
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        return $this->client->post("batches", [
            "json" => $body,
        ]);
    }

    public function retrieve(string $batch_id)
    {
        return $this->client->get("batches/{$batch_id}");
    }

    public function list(array $query = [])
    {
        return $this->client->get("batches", [
            "query" => $query,
        ]);
    }
}
