<?php

namespace OpenAI;

use Exception;
use Psr\Http\Message\ResponseInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\ThroughStream;

class Responses
{

    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        return  $this->client->post("responses", [
            "json" => $body,
        ]);
    }



    public function createStream(array $body)
    {
        $browser = new \React\Http\Browser();
        $body["stream"] = true;
        $promise = $browser->requestStreaming("POST", $this->client->base_url . "responses", [
            "Authorization" => "Bearer " . $this->client->openai_api_key,
            "Content-Type" => "application/json",
        ], json_encode($body, JSON_UNESCAPED_UNICODE));

        $stream = new ResponseEventEmitter();

        $promise->then(function (ResponseInterface $response) use ($stream) {
            $s = $response->getBody();
            assert($s instanceof ReadableStreamInterface);


            $next_chunk = "";

            $s->on("data", function ($chunk) use (&$stream, &$next_chunk) {
                //echo $chunk;


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
                    $stream->write($line . "\n\n");
                }
            });
        }/* , function (Exception $e) use ($stream) {
            $stream->emit("error", [$e]);
            
           
        } */);



        return $stream;
    }
}
