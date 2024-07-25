<?php

namespace OpenAI;


class Embeddings
{
    private $client;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create an embedding
     * create([
     *  "model" => "text-embedding-3-small",
     *  "input" => "I feel great"
     * ])
     */

    public function create(array $options = [])
    {
        return $this->client->post("embeddings", [
            'json' => $options
        ]);
    }
  
}
