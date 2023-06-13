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

    public function transcribe(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            if ($name == "file") {
                $data[] = [
                    "name" => $name,
                    "contents" => \GuzzleHttp\Psr7\Utils::tryFopen($value, 'r')
                ];
            } else {
                $data[] = [
                    "name" => $name,
                    "contents" => $value
                ];
            }
        }

        $response = $this->client->post("audio/transcriptions", [
            "multipart" => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
