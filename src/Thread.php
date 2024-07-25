<?php

namespace OpenAI;

class Thread
{
    private $client;
    private $thread_id;

    public function __construct(Client $client, string $thread_id)
    {
        $this->client = $client;
        $this->thread_id = $thread_id;
    }

    public function delete()
    {
        return $this->client->delete("threads/" . $this->thread_id, [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function messages()
    {
        return new Messages($this->client, $this->thread_id);
    }

    public function run(string $assistant_id)
    {
        return $this->client->post("threads/" . $this->thread_id . "/runs", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
            "json" => [
                "assistant_id" => $assistant_id
            ]
        ]);
    }

    public function runs()
    {
        return new Runs($this->client, $this->thread_id);
    }
}
