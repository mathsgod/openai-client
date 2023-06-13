<?php

namespace OpenAI;

use GuzzleHttp\Client;

class Audio
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function translate(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            $data[] = [
                "name" => $name,
                "contents" => $value
            ];
        }

        $response = $this->client->post("audio/translations", [
            "multipart" => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function transcribe(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            $data[] = [
                "name" => $name,
                "contents" => $value
            ];
        }

        $response = $this->client->post("audio/transcriptions", [
            "multipart" => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
