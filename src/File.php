<?php

namespace OpenAI;

class File
{
    private $client;
    private $file_id;

    public function __construct(Client $client, string $file_id)
    {
        $this->client = $client;
        $this->file_id = $file_id;
    }

    public function create(array $body)
    {
        return $this->client->post('files', $body);

    }

    public function retrieve()
    {
        return $this->client->get("files/$this->file_id");
    }

    public function delete()
    {
        return $this->client->delete("files/$this->file_id");
    }

    public function content()
    {
        return $this->client->get("files/$this->file_id/content");
    }
}
