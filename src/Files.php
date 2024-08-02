<?php

namespace OpenAI;

class Files
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            $data[] = [
                "name" => $name,
                "contents" => $value
            ];
        }

        return $this->client->post("files", [
            "multipart" => $data
        ]);
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
