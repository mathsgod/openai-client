<?php

namespace OpenAI;

use GuzzleHttp\Client;

class Assistant
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list()
    {
        $response = $this->client->get("assistants", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v1"
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


    public function create(array $body)
    {
        $response = $this->client->post("assistants", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v1"
            ],
            "json" => $body
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete(string $assistant_id)
    {
        $response = $this->client->delete("assistants/" . $assistant_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v1"
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
