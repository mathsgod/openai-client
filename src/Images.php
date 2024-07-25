<?php

namespace OpenAI;


class Images
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function generations(array $body)
    {
        return $this->client->post("images/generations", [
            "json" => $body
        ]);
    }

    public function edits(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            $data[] = [
                "name" => $name,
                "contents" => $value
            ];
        }
        return $this->client->postRaw("audio/transcriptions", [
            "multipart" => $data
        ]);
    }
}
