<?php

namespace OpenAI;

use Psr\Http\Message\ResponseInterface;
use React\Stream\DuplexStreamInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\ThroughStream;

class Runs
{
    private $client;
    private $thread_id;


    public function __construct(Client $client, string $thread_id)
    {
        $this->client = $client;
        $this->thread_id = $thread_id;
    }

    public function list()
    {
        return $this->client->get("threads/$this->thread_id/runs", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }

    public function create(array $body)
    {
        return $this->client->post("threads/$this->thread_id/runs", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
            "json" => $body
        ]);
    }

    public function createStream(array $body): DuplexStreamInterface
    {
        $browser = new \React\Http\Browser();

        $body["stream"] = true;


        $promise = $browser->requestStreaming("POST", $this->client->base_url . "threads/$this->thread_id/runs", [
            "Authorization" => "Bearer " . $this->client->openai_api_key,
            "Content-Type" => "application/json",
            "OpenAI-Beta" => "assistants=v2"
        ], json_encode($body, JSON_UNESCAPED_UNICODE));

        $stream = new ThroughStream();

        $promise->then(function (ResponseInterface $response) use (&$stream) {
            $s = $response->getBody();
            assert($s instanceof ReadableStreamInterface);

            $s->on("data", function ($data) use (&$stream) {
                $stream->write($data);
            });

            $s->on("end", function () use (&$stream) {
                $stream->end();
            });

            $s->on("close", function () use (&$stream) {
                $stream->close();
            });
        });

        return $stream;
    }
}
