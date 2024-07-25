<?php

namespace OpenAI;


class Audio
{
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function speech(array $body)
    {
        return $this->client->postRaw("audio/speech", [
            "json" => $body
        ]);
    }
    public function transcriptions(array $body)
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

    public function translations(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            $data[] = [
                "name" => $name,
                "contents" => $value
            ];
        }

        return $this->client->postRaw("audio/translations", [
            "multipart" => $data
        ]);
    }
}
