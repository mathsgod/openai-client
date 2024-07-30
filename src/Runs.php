<?php

namespace OpenAI;

use Psr\Http\Message\ResponseInterface;
use React\Promise\PromiseInterface;
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

    public static function ProcessPromise(PromiseInterface $promise): DuplexStreamInterface
    {

        $stream = new ThroughStream();

        $promise->then(function (ResponseInterface $response) use (&$stream) {
            $s = $response->getBody();
            assert($s instanceof ReadableStreamInterface);

            $next_chunk = "";

            $s->on("data", function ($chunk) use (&$stream, &$next_chunk) {


                $chunk = $next_chunk . $chunk;
                $lines = explode("\n\n", $chunk);

                //if last line is not empty, then it is not complete, only process the lines without last line
                if ($lines[count($lines) - 1]) {
                    $next_chunk = array_pop($lines);
                } else {
                    $next_chunk = "";
                }

                //filter out empty lines
                $lines = array_filter($lines);

                foreach ($lines as $line) {
                    //process the lines

                    list($event, $data) = explode("\n", $line, 2);

                    //parse $event 
                    $event = substr($event, 7);
                    $stream->emit($event, [$data]);
                }
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

    public function createStream(array $body): DuplexStreamInterface
    {
        $browser = new \React\Http\Browser();

        $body["stream"] = true;


        $promise = $browser->requestStreaming("POST", $this->client->base_url . "threads/$this->thread_id/runs", [
            "Authorization" => "Bearer " . $this->client->openai_api_key,
            "Content-Type" => "application/json",
            "OpenAI-Beta" => "assistants=v2"
        ], json_encode($body, JSON_UNESCAPED_UNICODE));


        return self::ProcessPromise($promise);
    }
}
