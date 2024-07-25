<?php

namespace OpenAI;

class Files
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list()
    {
        return $this->client->get("files")["data"];
    }


    public function retrieve(string $file_id)
    {
        return $this->client->get("files/$file_id");
    }

    public function delete(string $file_id)
    {
        return $this->client->delete("files/$file_id");
    }
}
